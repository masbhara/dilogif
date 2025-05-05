<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('whatsapp_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama template (identifikasi)
            $table->enum('type', ['customer', 'admin']); // Tipe template: customer atau admin
            $table->enum('trigger_status', [
                'new_order', 
                'pending', 
                'processing', 
                'review',
                'completed',
                'cancelled',
                'payment_confirmed',
                'shipped'
            ]); // Status yang memicu
            $table->text('message_template'); // Template pesan dengan variabel {order_number} dll
            $table->text('description')->nullable(); // Deskripsi penggunaan template
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0); // Urutan template
            $table->timestamps();

            // Composite unique key berdasarkan type dan trigger_status
            $table->unique(['type', 'trigger_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_templates');
    }
};
