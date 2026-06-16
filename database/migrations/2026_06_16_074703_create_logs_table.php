<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('logs', function (Blueprint $table) {
        $table->id();
        $table->string('level');           // debug, info, warning, error, etc.
        $table->text('message');
        $table->json('context')->nullable();
        $table->json('extra')->nullable();
        $table->string('channel')->default('stack');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
