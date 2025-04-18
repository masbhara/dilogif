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
        Schema::table('orders', function (Blueprint $table) {
            // Make user_id nullable for guest checkout
            $table->foreignId('user_id')->nullable()->change();
            
            // Customer information
            $table->string('customer_name')->after('user_id');
            $table->string('customer_phone')->after('customer_name');
            $table->string('customer_email')->nullable()->after('customer_phone');
            
            // Additional price fields
            $table->decimal('subtotal', 10, 2)->after('total_amount');
            $table->decimal('admin_fee', 10, 2)->default(0)->after('subtotal');
            $table->decimal('discount', 10, 2)->default(0)->after('admin_fee');
            
            // Modify status field with more specific statuses
            $table->string('status')->default('pending')
                ->comment('pending, processing, review, completed, cancelled')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('user_id')->change();
            $table->dropColumn([
                'customer_name',
                'customer_phone',
                'customer_email',
                'subtotal',
                'admin_fee',
                'discount'
            ]);
        });
    }
};
