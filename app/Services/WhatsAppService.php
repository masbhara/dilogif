<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private $apiKey;
    private $webhookUrl;
    private $isActive;

    public function __construct()
    {
        // Ambil pengaturan dari database
        $settings = WebsiteSetting::first();
        
        $this->apiKey = $settings->webhook_api_key ?? env('DRIPSENDER_API_KEY');
        $this->webhookUrl = $settings->webhook_url ?? env('DRIPSENDER_WEBHOOK_URL');
        $this->isActive = $settings->webhook_is_active ?? false;
    }

    /**
     * Mengirim notifikasi WhatsApp saat order dibuat
     * 
     * @param Order $order
     * @return bool
     */
    public function sendOrderCreatedNotification(Order $order)
    {
        try {
            if (!$this->isActive) {
                Log::info('WhatsApp notification disabled: Order created notification not sent');
                return false;
            }

            if (empty($order->customer_phone)) {
                Log::warning('Tidak dapat mengirim notifikasi WhatsApp: Nomor telepon pelanggan kosong', [
                    'order_id' => $order->id
                ]);
                return false;
            }

            // Format nomor telepon (pastikan diawali dengan kode negara tanpa +)
            $phone = $this->formatPhoneNumber($order->customer_phone);

            // Data untuk notifikasi
            $items = $order->items->map(function ($item) {
                return "- {$item->quantity}x {$item->product_name} @ " . number_format($item->price, 0, ',', '.') . " = " . number_format($item->price * $item->quantity, 0, ',', '.');
            })->join("\n");

            $message = "✅ *PESANAN BERHASIL DIBUAT*\n\n"
                . "*No. Order:* #{$order->order_number}\n"
                . "*Tanggal:* " . $order->created_at->format('d/m/Y H:i') . "\n"
                . "*Pelanggan:* {$order->customer_name}\n\n"
                . "*DETAIL PESANAN*\n"
                . $items . "\n\n"
                . "*SubTotal:* Rp" . number_format($order->subtotal, 0, ',', '.') . "\n"
                . "*Pengiriman:* Rp" . number_format($order->shipping_cost, 0, ',', '.') . "\n"
                . "*Total:* Rp" . number_format($order->total, 0, ',', '.') . "\n\n"
                . "Silakan lakukan pembayaran sesuai dengan instruksi yang diberikan. Terima kasih!";

            // Kirim ke webhook
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json'
            ])->post($this->webhookUrl, [
                'name' => $order->customer_name,
                'phone' => $phone,
                'message' => $message
            ]);

            if ($response->successful()) {
                Log::info('Notifikasi WhatsApp terkirim: Order dibuat', [
                    'order_id' => $order->id,
                    'response' => $response->json()
                ]);
                return true;
            } else {
                Log::error('Gagal mengirim notifikasi WhatsApp: Order dibuat', [
                    'order_id' => $order->id,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Error saat mengirim notifikasi WhatsApp: Order dibuat', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Mengirim notifikasi WhatsApp saat status order berubah
     * 
     * @param Order $order
     * @param string $oldStatus
     * @return bool
     */
    public function sendOrderStatusChangedNotification(Order $order, $oldStatus)
    {
        try {
            if (!$this->isActive) {
                Log::info('WhatsApp notification disabled: Order status change notification not sent');
                return false;
            }

            if (empty($order->customer_phone)) {
                Log::warning('Tidak dapat mengirim notifikasi WhatsApp: Nomor telepon pelanggan kosong', [
                    'order_id' => $order->id
                ]);
                return false;
            }

            // Format nomor telepon
            $phone = $this->formatPhoneNumber($order->customer_phone);

            // Dapatkan status yang mudah dibaca
            $statusText = $this->getReadableStatus($order->status);
            
            // Data untuk notifikasi
            $message = "🔔 *UPDATE STATUS PESANAN*\n\n"
                . "*No. Order:* #{$order->order_number}\n"
                . "*Status:* {$statusText}\n\n";

            // Tambahkan pesan khusus berdasarkan status
            switch ($order->status) {
                case Order::STATUS_PROCESSING:
                    $message .= "Pesanan Anda sedang diproses. Mohon tunggu informasi selanjutnya.";
                    break;
                case Order::STATUS_SHIPPED:
                    $message .= "Pesanan Anda telah dikirim. ";
                    if ($order->tracking_number) {
                        $message .= "Nomor resi: *{$order->tracking_number}*";
                    }
                    break;
                case Order::STATUS_COMPLETED:
                    $message .= "Pesanan Anda telah selesai. Terima kasih telah berbelanja!";
                    break;
                case Order::STATUS_CANCELLED:
                    $message .= "Pesanan Anda telah dibatalkan. Silakan hubungi kami untuk informasi lebih lanjut.";
                    break;
                default:
                    $message .= "Silakan hubungi kami jika ada pertanyaan.";
            }

            // Kirim ke webhook
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json'
            ])->post($this->webhookUrl, [
                'name' => $order->customer_name,
                'phone' => $phone,
                'message' => $message
            ]);

            if ($response->successful()) {
                Log::info('Notifikasi WhatsApp terkirim: Status order berubah', [
                    'order_id' => $order->id,
                    'old_status' => $oldStatus,
                    'new_status' => $order->status,
                    'response' => $response->json()
                ]);
                return true;
            } else {
                Log::error('Gagal mengirim notifikasi WhatsApp: Status order berubah', [
                    'order_id' => $order->id,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Error saat mengirim notifikasi WhatsApp: Status order berubah', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Mengirim notifikasi WhatsApp saat pembayaran dikonfirmasi
     * 
     * @param Order $order
     * @return bool
     */
    public function sendPaymentConfirmedNotification(Order $order)
    {
        try {
            if (!$this->isActive) {
                Log::info('WhatsApp notification disabled: Payment confirmed notification not sent');
                return false;
            }

            if (empty($order->customer_phone)) {
                Log::warning('Tidak dapat mengirim notifikasi WhatsApp: Nomor telepon pelanggan kosong', [
                    'order_id' => $order->id
                ]);
                return false;
            }

            // Format nomor telepon
            $phone = $this->formatPhoneNumber($order->customer_phone);

            // Data untuk notifikasi
            $message = "💰 *PEMBAYARAN DIKONFIRMASI*\n\n"
                . "*No. Order:* #{$order->order_number}\n"
                . "*Tanggal:* " . now()->format('d/m/Y H:i') . "\n"
                . "*Total:* Rp" . number_format($order->total, 0, ',', '.') . "\n\n"
                . "Pembayaran Anda telah dikonfirmasi. Pesanan Anda akan segera diproses. Terima kasih!";

            // Kirim ke webhook
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json'
            ])->post($this->webhookUrl, [
                'name' => $order->customer_name,
                'phone' => $phone,
                'message' => $message
            ]);

            if ($response->successful()) {
                Log::info('Notifikasi WhatsApp terkirim: Pembayaran dikonfirmasi', [
                    'order_id' => $order->id,
                    'response' => $response->json()
                ]);
                return true;
            } else {
                Log::error('Gagal mengirim notifikasi WhatsApp: Pembayaran dikonfirmasi', [
                    'order_id' => $order->id,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Error saat mengirim notifikasi WhatsApp: Pembayaran dikonfirmasi', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Format nomor telepon untuk memastikan format yang benar (62xxx)
     * 
     * @param string $phoneNumber
     * @return string
     */
    private function formatPhoneNumber($phoneNumber)
    {
        // Hapus karakter non-digit
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Jika dimulai dengan 0, ganti dengan kode negara Indonesia (62)
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        // Jika tidak dimulai dengan kode negara, tambahkan 62
        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    /**
     * Mendapatkan status yang mudah dibaca
     * 
     * @param string $status
     * @return string
     */
    private function getReadableStatus($status)
    {
        $statusMap = [
            Order::STATUS_PENDING => 'Menunggu Pembayaran',
            Order::STATUS_PROCESSING => 'Sedang Diproses',
            Order::STATUS_SHIPPED => 'Telah Dikirim',
            Order::STATUS_COMPLETED => 'Selesai',
            Order::STATUS_CANCELLED => 'Dibatalkan',
            Order::STATUS_REVIEW => 'Menunggu Review',
        ];

        return $statusMap[$status] ?? $status;
    }
} 