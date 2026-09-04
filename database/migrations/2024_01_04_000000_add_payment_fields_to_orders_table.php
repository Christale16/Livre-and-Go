<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // mtn_momo = MTN Mobile Money, moov_celtiis = Celtiis Cash, bank_transfer = compte bancaire
            $table->enum('payment_method', ['mtn_momo', 'moov_celtiis', 'bank_transfer'])
                  ->nullable()
                  ->after('price');

            // Référence/numéro de transaction fourni par le client après son paiement
            $table->string('payment_reference')->nullable()->after('payment_method');

            // non_paye = pas encore confirmé, paye = paiement confirmé
            $table->enum('payment_status', ['non_paye', 'paye'])
                  ->default('non_paye')
                  ->after('payment_reference');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_reference', 'payment_status']);
        });
    }
};
