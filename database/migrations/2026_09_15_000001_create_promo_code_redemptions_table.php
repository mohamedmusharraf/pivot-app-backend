<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_code_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_code_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('product_id');
            $table->string('transaction_id')->nullable()->unique();
            $table->string('status')->default('validated');
            $table->timestamp('validated_at')->nullable();
            $table->timestamp('redeemed_at')->nullable();
            $table->timestamps();
            $table->index(['promo_code_id', 'status']);
            $table->index(['promo_code_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_code_redemptions');
    }
};
