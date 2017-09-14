<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->uuid('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->tinyInteger('role')->default(2)->comment = '1:super admin, 2:user';
            $table->string('avatar', 255)->nullable();
            $table->datetime('register_date')->nullable();
            $table->datetime('last_login_date')->nullable();
            $table->tinyInteger('status')->default(1)->comment = 'unconfirmed:1, active:2, 9:banned';
            $table->string('phone', 50)->nullable();
            $table->string('address', 255)->nullable();
            $table->text('biography')->nullable();
            $table->string('register_ip', 45)->nullable();
            $table->datetime('activation_date')->nullable();
            $table->boolean('verified')->default(false);
            $table->string('verification_code', 255)->nullable();
            $table->softDeletes();
            $table->timestamps();
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
