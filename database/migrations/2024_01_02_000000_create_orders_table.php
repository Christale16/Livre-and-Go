<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('livreur_id')->nullable()->constrained('users')->onDelete('set null');

            $table->string('pickup_address');
            $table->decimal('pickup_lat', 10, 7)->nullable();
            $table->decimal('pickup_lng', 10, 7)->nullable();

            $table->string('delivery_address');
            $table->decimal('delivery_lat', 10, 7)->nullable();
            $table->decimal('delivery_lng', 10, 7)->nullable();

            $table->text('description')->nullable();
            $table->decimal('price', 8, 2)->nullable();

            // pending = en attente d'un livreur, accepted = acceptée, refused = refusée,
            // in_progress = en cours de livraison, delivered = livrée, cancelled = annulée
            $table->enum('status', ['pending', 'accepted', 'refused', 'in_progress', 'delivered', 'cancelled'])
                  ->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
