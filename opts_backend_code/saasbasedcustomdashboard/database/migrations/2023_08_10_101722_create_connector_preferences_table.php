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
        Schema::create('connector_preferences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('connector_type', [
                ConnectorEnum::GOOGLESHEET->value,
                ConnectorEnum::QUICKBOOKS->value,
                ConnectorEnum::SALESFORCE->value
            ]);
            $table->integer('year')->nullable();
            $table->string('field_name');
            $table->string('field_value');
            $table->jsonb('extras')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connector_preferences');
    }
};
