<?php

namespace Database\Seeders;

use App\Models\WhatsAppTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WhatsAppTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            // Template untuk customer
            [
                'name' => 'Order Baru',
                'type' => WhatsAppTemplate::TYPE_CUSTOMER,
                'trigger_status' => WhatsAppTemplate::TRIGGER_NEW_ORDER,
                'message_template' => "✅ *PESANAN BERHASIL DIBUAT*\n\n"
                    . "*No. Order:* #{order_number}\n"
                    . "*Tanggal:* {order_date}\n"
                    . "*Pelanggan:* {customer_name}\n\n"
                    . "*DETAIL PESANAN*\n"
                    . "{items_list}\n\n"
                    . "*SubTotal:* {subtotal}\n"
                    . "*Total:* {total_amount}\n\n"
                    . "Silakan lakukan pembayaran sesuai dengan instruksi yang diberikan. Terima kasih!",
                'description' => 'Template untuk notifikasi order baru ke customer',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'Pembayaran Dikonfirmasi',
                'type' => WhatsAppTemplate::TYPE_CUSTOMER,
                'trigger_status' => WhatsAppTemplate::TRIGGER_PAYMENT_CONFIRMED,
                'message_template' => "💰 *PEMBAYARAN DIKONFIRMASI*\n\n"
                    . "*No. Order:* #{order_number}\n"
                    . "*Tanggal:* {order_date}\n"
                    . "*Total:* {total_amount}\n\n"
                    . "Pembayaran Anda telah dikonfirmasi. Pesanan Anda akan segera diproses. Terima kasih!",
                'description' => 'Template untuk notifikasi pembayaran dikonfirmasi ke customer',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Order Diproses',
                'type' => WhatsAppTemplate::TYPE_CUSTOMER,
                'trigger_status' => WhatsAppTemplate::TRIGGER_PROCESSING,
                'message_template' => "🔔 *UPDATE STATUS PESANAN*\n\n"
                    . "*No. Order:* #{order_number}\n"
                    . "*Status:* {status}\n\n"
                    . "Pesanan Anda sedang diproses. Mohon tunggu informasi selanjutnya.",
                'description' => 'Template untuk notifikasi status pesanan diproses ke customer',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name' => 'Order Selesai',
                'type' => WhatsAppTemplate::TYPE_CUSTOMER,
                'trigger_status' => WhatsAppTemplate::TRIGGER_COMPLETED,
                'message_template' => "🔔 *UPDATE STATUS PESANAN*\n\n"
                    . "*No. Order:* #{order_number}\n"
                    . "*Status:* {status}\n\n"
                    . "Pesanan Anda telah selesai. Terima kasih telah berbelanja!",
                'description' => 'Template untuk notifikasi status pesanan selesai ke customer',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'name' => 'Order Dibatalkan',
                'type' => WhatsAppTemplate::TYPE_CUSTOMER,
                'trigger_status' => WhatsAppTemplate::TRIGGER_CANCELLED,
                'message_template' => "🔔 *UPDATE STATUS PESANAN*\n\n"
                    . "*No. Order:* #{order_number}\n"
                    . "*Status:* {status}\n\n"
                    . "Pesanan Anda telah dibatalkan. Silakan hubungi kami untuk informasi lebih lanjut.",
                'description' => 'Template untuk notifikasi status pesanan dibatalkan ke customer',
                'is_active' => true,
                'order' => 5,
            ],
            
            // Template untuk admin
            [
                'name' => 'Notifikasi Order Baru ke Admin',
                'type' => WhatsAppTemplate::TYPE_ADMIN,
                'trigger_status' => WhatsAppTemplate::TRIGGER_NEW_ORDER,
                'message_template' => "🔔 *ORDER BARU*\n\n"
                    . "*No. Order:* #{order_number}\n"
                    . "*Tanggal:* {order_date}\n"
                    . "*Pelanggan:* {customer_name}\n"
                    . "*Telepon:* {customer_phone}\n\n"
                    . "*DETAIL PESANAN*\n"
                    . "{items_list}\n\n"
                    . "*Total:* {total_amount}\n\n"
                    . "Silakan cek dashboard admin untuk detail lebih lanjut.",
                'description' => 'Template untuk notifikasi order baru ke admin',
                'is_active' => true,
                'order' => 6,
            ],
            [
                'name' => 'Notifikasi Konfirmasi Pembayaran ke Admin',
                'type' => WhatsAppTemplate::TYPE_ADMIN,
                'trigger_status' => WhatsAppTemplate::TRIGGER_PAYMENT_CONFIRMED,
                'message_template' => "💰 *KONFIRMASI PEMBAYARAN*\n\n"
                    . "*No. Order:* #{order_number}\n"
                    . "*Pelanggan:* {customer_name}\n"
                    . "*Total:* {total_amount}\n\n"
                    . "Ada konfirmasi pembayaran baru. Silakan cek dashboard admin untuk verifikasi.",
                'description' => 'Template untuk notifikasi pembayaran baru ke admin',
                'is_active' => true,
                'order' => 7,
            ],
        ];
        
        foreach ($templates as $template) {
            WhatsAppTemplate::updateOrCreate(
                ['type' => $template['type'], 'trigger_status' => $template['trigger_status']],
                $template
            );
        }
    }
}
