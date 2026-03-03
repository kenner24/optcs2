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
        Schema::create('sales_force_opportunities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('opportunity_id')->unique();
            $table->string('name');
            $table->string('source')->nullable();
            $table->string('stage')->nullable();
            $table->dateTimeTz('opportunity_created_date');
            $table->dateTime('opportunity_close_date');
            $table->decimal('amount', 20, 3)->nullable()->default(0);
            $table->decimal('expected_revenue', 20, 3)->nullable()->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_force_opportunities');
    }
};
