<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\WebsiteSetting;
use App\Models\AdminWhatsapp;
use App\Models\WhatsAppTemplate;
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
            
            // Ambil template dari database
            $template = WhatsAppTemplate::getTemplate(
                WhatsAppTemplate::TYPE_CUSTOMER, 
                WhatsAppTemplate::TRIGGER_NEW_ORDER
            );
            
            // Format nomor telepon (pastikan diawali dengan kode negara tanpa +)
            $phone = $this->formatPhoneNumber($order->customer_phone);
            
            // Jika template tidak ditemukan, gunakan pesan default
            if (!$template) {
                Log::warning('Template notifikasi WhatsApp tidak ditemukan untuk order baru ke customer, menggunakan pesan default');
                
                // Data untuk notifikasi default
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
            } else {
                // Gunakan template dari database
                $message = $template->parseTemplate($order);
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
                Log::info('Notifikasi WhatsApp terkirim: Order dibuat ke customer', [
                    'order_id' => $order->id,
                    'phone' => $phone,
                    'response' => $response->json()
                ]);
                
                // Kirim juga notifikasi ke admin
                $this->sendAdminNotification($order, WhatsAppTemplate::TRIGGER_NEW_ORDER);
                
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
            
            // Jika status tidak berubah, tidak perlu kirim notifikasi
            if ($oldStatus === $order->status) {
                Log::info('Status tidak berubah, tidak mengirim notifikasi WhatsApp', [
                    'order_id' => $order->id,
                    'status' => $order->status
                ]);
                return true;
            }

            // Format nomor telepon
            $phone = $this->formatPhoneNumber($order->customer_phone);
            
            // Ambil template berdasarkan status baru
            $triggerStatus = $this->mapOrderStatusToTrigger($order->status);
            $template = WhatsAppTemplate::getTemplate(
                WhatsAppTemplate::TYPE_CUSTOMER,
                $triggerStatus
            );
            
            // Jika template tidak ditemukan, buat pesan default
            if (!$template) {
                Log::warning("Template notifikasi WhatsApp tidak ditemukan untuk status {$triggerStatus} ke customer, menggunakan pesan default");
                
                // Dapatkan status yang mudah dibaca
                $statusText = $this->getReadableStatus($order->status);
                
                // Data untuk notifikasi default
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
            } else {
                // Gunakan template dari database
                $message = $template->parseTemplate($order);
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
            
            // Ambil template dari database
            $template = WhatsAppTemplate::getTemplate(
                WhatsAppTemplate::TYPE_CUSTOMER,
                WhatsAppTemplate::TRIGGER_PAYMENT_CONFIRMED
            );
            
            // Jika template tidak ditemukan, gunakan pesan default
            if (!$template) {
                Log::warning('Template notifikasi WhatsApp tidak ditemukan untuk konfirmasi pembayaran ke customer, menggunakan pesan default');
                
                // Data untuk notifikasi default
                $message = "💰 *PEMBAYARAN DIKONFIRMASI*\n\n"
                    . "*No. Order:* #{$order->order_number}\n"
                    . "*Tanggal:* " . now()->format('d/m/Y H:i') . "\n"
                    . "*Total:* Rp" . number_format($order->total, 0, ',', '.') . "\n\n"
                    . "Pembayaran Anda telah dikonfirmasi. Pesanan Anda akan segera diproses. Terima kasih!";
            } else {
                // Gunakan template dari database
                $message = $template->parseTemplate($order);
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
                Log::info('Notifikasi WhatsApp terkirim: Pembayaran dikonfirmasi ke customer', [
                    'order_id' => $order->id,
                    'phone' => $phone,
                    'response' => $response->json()
                ]);
                
                // Kirim juga notifikasi ke admin
                $this->sendAdminNotification($order, WhatsAppTemplate::TRIGGER_PAYMENT_CONFIRMED);
                
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

    /**
     * Mengirim notifikasi WhatsApp ke admin
     * 
     * @param Order $order
     * @param string $triggerStatus
     * @return bool
     */
    public function sendAdminNotification(Order $order, string $triggerStatus): bool
    {
        try {
            // Dapatkan semua admin WhatsApp yang aktif
            $adminWhatsApps = AdminWhatsapp::where('is_active', true)
                ->orderBy('order')
                ->get();
                
            if ($adminWhatsApps->isEmpty()) {
                Log::info('Tidak ada nomor WhatsApp admin yang aktif');
                return false;
            }
            
            // Ambil template admin
            $template = WhatsAppTemplate::getTemplate(
                WhatsAppTemplate::TYPE_ADMIN,
                $triggerStatus
            );
            
            if (!$template) {
                Log::warning("Template notifikasi WhatsApp admin tidak ditemukan untuk trigger {$triggerStatus}");
                
                // Gunakan pesan default jika template tidak ditemukan
                $message = $this->getDefaultAdminMessage($order, $triggerStatus);
            } else {
                // Dapatkan pesan yang sudah diisi dengan data order
                $message = $template->parseTemplate($order);
            }
            
            $sentCount = 0;
            
            // Kirim ke semua admin
            foreach ($adminWhatsApps as $admin) {
                $phone = $this->formatPhoneNumber($admin->phone_number);
                
                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Content-Type' => 'application/json'
                ])->post($this->webhookUrl, [
                    'name' => $admin->name,
                    'phone' => $phone,
                    'message' => $message
                ]);
                
                if ($response->successful()) {
                    Log::info("Notifikasi WhatsApp terkirim ke admin {$admin->name}", [
                        'order_id' => $order->id,
                        'admin_id' => $admin->id,
                        'phone' => $admin->phone_number,
                        'response' => $response->json()
                    ]);
                    $sentCount++;
                } else {
                    Log::error("Gagal mengirim notifikasi WhatsApp ke admin {$admin->name}", [
                        'order_id' => $order->id,
                        'admin_id' => $admin->id,
                        'status' => $response->status(),
                        'response' => $response->body()
                    ]);
                }
            }
            
            Log::info("Notifikasi admin berhasil dikirim ke {$sentCount} dari {$adminWhatsApps->count()} admin");
            return $sentCount > 0;
        } catch (\Exception $e) {
            Log::error('Error saat mengirim notifikasi WhatsApp ke admin', [
                'order_id' => $order->id,
                'trigger' => $triggerStatus,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Mendapatkan pesan default untuk admin jika template tidak tersedia
     *
     * @param Order $order
     * @param string $triggerStatus
     * @return string
     */
    private function getDefaultAdminMessage(Order $order, string $triggerStatus): string
    {
        $items = $order->items->map(function ($item) {
            return "- {$item->quantity}x {$item->product_name}: Rp " . number_format($item->subtotal, 0, ',', '.');
        })->join("\n");
        
        switch ($triggerStatus) {
            case WhatsAppTemplate::TRIGGER_NEW_ORDER:
                return "🔔 *ORDER BARU*\n\n"
                    . "*No. Order:* #{$order->order_number}\n"
                    . "*Tanggal:* " . $order->created_at->format('d/m/Y H:i') . "\n"
                    . "*Pelanggan:* {$order->customer_name}\n"
                    . "*Telepon:* {$order->customer_phone}\n\n"
                    . "*DETAIL PESANAN*\n"
                    . $items . "\n\n"
                    . "*Total:* Rp" . number_format($order->total, 0, ',', '.') . "\n\n"
                    . "Silakan cek dashboard admin untuk detail lebih lanjut.";
                    
            case WhatsAppTemplate::TRIGGER_PAYMENT_CONFIRMED:
                return "💰 *KONFIRMASI PEMBAYARAN*\n\n"
                    . "*No. Order:* #{$order->order_number}\n"
                    . "*Pelanggan:* {$order->customer_name}\n"
                    . "*Total:* Rp" . number_format($order->total, 0, ',', '.') . "\n\n"
                    . "Ada konfirmasi pembayaran baru. Silakan cek dashboard admin untuk verifikasi.";
                    
            default:
                return "🔔 *NOTIFIKASI ORDER*\n\n"
                    . "*No. Order:* #{$order->order_number}\n"
                    . "*Pelanggan:* {$order->customer_name}\n"
                    . "*Status:* " . $this->getReadableStatus($order->status) . "\n\n"
                    . "Silakan cek dashboard admin untuk detail lebih lanjut.";
        }
    }
    
    /**
     * Mapping status order ke trigger status template
     *
     * @param string $orderStatus
     * @return string
     */
    private function mapOrderStatusToTrigger(string $orderStatus): string
    {
        $map = [
            Order::STATUS_PENDING => WhatsAppTemplate::TRIGGER_PENDING,
            Order::STATUS_PROCESSING => WhatsAppTemplate::TRIGGER_PROCESSING,
            Order::STATUS_REVIEW => WhatsAppTemplate::TRIGGER_REVIEW,
            Order::STATUS_COMPLETED => WhatsAppTemplate::TRIGGER_COMPLETED,
            Order::STATUS_CANCELLED => WhatsAppTemplate::TRIGGER_CANCELLED,
            Order::STATUS_SHIPPED => WhatsAppTemplate::TRIGGER_SHIPPED,
        ];
        
        return $map[$orderStatus] ?? WhatsAppTemplate::TRIGGER_PROCESSING;
    }
} 