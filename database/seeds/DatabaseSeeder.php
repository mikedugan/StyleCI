<?php

/*
 * This file is part of StyleCI.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Seeder;

/**
 * This is the database seeder class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeding.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        //
    }
}
