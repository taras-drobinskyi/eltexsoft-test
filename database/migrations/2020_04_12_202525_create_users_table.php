<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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
            $table->string('status');
            $table->string('email')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->timestamp('date_of_birth');
            $table->timestamp('last_login')->nullable();
            $table->text('notes');
            $table->text('avatar')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('api_token');
            $table->rememberToken();
            $table->timestamps();
        });

        $token = Str::random(60);

        DB::table('users')->insert(
            [
                'status' => 'active',
                'email' => 'admin@eltexsoft.com',
                'first_name' => 'Taras',
                'last_name' => 'Dr',
                'date_of_birth' => Carbon::createFromDate(1990,5,1)->toDateTime(),
                'last_login' => Carbon::now()->toDateTime(),
                'notes' => 'First admin',
                'password' => bcrypt('password'),
                'api_token' => hash('sha256', $token),
            ]
        );
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
