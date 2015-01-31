<?php

/*
* This file is part of StyleCI.
*
* (c) Graham Campbell <graham@mineuk.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * This is the create create services table migration class.
 *
 * @author Joseph Cohen <joseph.cohen@dinkbit.com>
 */
class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id');
            $table->string('uid');
            $table->string('provider');
            $table->string('oauth1_token_identifier')->nullable();
            $table->string('oauth1_token_secret')->nullable();
            $table->string('oauth2_access_token')->nullable();
            // $table->string('oauth2_refresh_token')->nullable();
            // $table->timestamp('oauth2_expires')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->unique(['provider', 'uid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('services');
    }
}
