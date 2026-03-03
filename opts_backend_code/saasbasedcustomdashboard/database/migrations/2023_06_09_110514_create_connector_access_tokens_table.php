<?php

use App\Enums\ConnectorEnum;
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
        Schema::create('connector_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->text("access_token");
            $table->text("refresh_token")->nullable();
            $table->timestamp("access_token_expired_at")->nullable();
            $table->timestamp("refresh_token_expired_at")->nullable();
            $table->timestamp("issued_at")->nullable();
            $table->enum('connector_type', [
                ConnectorEnum::GOOGLESHEET->value,
                ConnectorEnum::QUICKBOOKS->value,
                ConnectorEnum::SALESFORCE->value
            ]);
            $table->string("quick_book_realmId")->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connector_access_tokens');
    }
};
