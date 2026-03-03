<?php

use App\Enums\MonthEnum;
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
        Schema::create('quick_book_profitabilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('month', [
                MonthEnum::JANUARY->value,
                MonthEnum::FEBRUARY->value,
                MonthEnum::MARCH->value,
                MonthEnum::APRIL->value,
                MonthEnum::MAY->value,
                MonthEnum::JUNE->value,
                MonthEnum::JULY->value,
                MonthEnum::AUGUST->value,
                MonthEnum::SEPTEMBER->value,
                MonthEnum::OCTOBER->value,
                MonthEnum::NOVEMBER->value,
                MonthEnum::DECEMBER->value,
            ]);
            $table->integer('year');
            $table->decimal('percentage', 10, 2)->default(0);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quick_book_profitabilities');
    }
};
