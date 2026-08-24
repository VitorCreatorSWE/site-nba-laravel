<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('transaction_id')->unique()->nullable();
            $table->enum('status', ['pending', 'confirmed', 'failed'])->default('pending');
            $table->text('qr_code_payload')->nullable();
            $table->text('qr_code_image')->nullable();
            $table->string('donor_name')->nullable();
            $table->string('donor_email')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};