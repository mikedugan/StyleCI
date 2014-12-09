<?php

/**
 * This file is part of StyleCI by Graham Campbell.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * This is the create commits table migration class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/StyleCI/blob/master/LICENSE.md> AGPL 3.0
 */
class CreateCommitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commits', function (Blueprint $table) {
            $table->char('id', 40)->primary();
            $table->char('repo_id', 40);
            $table->string('ref', 128);
            $table->string('message', 128);
            $table->string('email', 128);
            $table->tinyInteger('status')->unsigned()->default(0);
            $table->float('time');
            $table->float('memory');
            $table->longText('diff');
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
        Schema::drop('commits');
    }
}
