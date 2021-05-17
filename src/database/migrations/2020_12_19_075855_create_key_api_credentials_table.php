<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeyApiCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('key_api_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('key_client_id')->constrained()->cascadeOnDelete();
            $table->longText('public_key');
            $table->longText('private_key');
            $table->longText('secret_hash')->nullable();
            $table->enum('status', ['active', 'suspended'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('api_keys');
    }
}
