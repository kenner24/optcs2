<?php

use App\Enums\UserStatusEnum;
use App\Enums\UserTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->text('provider_name')->nullable();
            $table->text('provider_id')->nullable();
            $table->string('slug')->nullable();
            $table->string('username')->nullable();
            $table->string('email_token')->nullable();
            $table->string('reset_pass_token')->nullable();
            $table->enum('type', [
                UserTypeEnum::SUPER_ADMIN->value,
                UserTypeEnum::COMPANY->value,
                UserTypeEnum::STAFF->value
            ])->nullable();
            $table->string('image')->nullable();
            $table->enum('status', [
                UserStatusEnum::ACTIVE->value,
                UserStatusEnum::IN_ACTIVE->value
            ])->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('company_name')->nullable();
            $table->string('work_email')->nullable();
            $table->bigInteger('total_employees')->nullable();
            $table->string('domain_sector')->nullable();
            $table->string("quick_book_realmId")->nullable();
            $table->jsonb('extras')->nullable();
            $table->timestamps();
            
            $table->foreign('company_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
