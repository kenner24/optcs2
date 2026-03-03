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
        Schema::create('sales_force_leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('lead_id')->unique();
            $table->string('name');
            $table->string('status');
            $table->string('source');
            $table->dateTimeTz('lead_created_date');
            $table->dateTimeTz('lead_converted_date')->nullable();
            $table->decimal('annual_revenue', 20, 3)->nullable()->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_force_leads');
    }
};
