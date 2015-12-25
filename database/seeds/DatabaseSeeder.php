<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vader = DB::table('users')->insert([
            'name'   => 'Darth_Vader',
            'email'      => 'darthv@deathstar.com',
            'password'   => Hash::make('thedarkside'),
            'created_at' => new DateTime(),
            'updated_at' => new DateTime()
        ]);

        DB::table('users')->insert([
            'name'   => 'goodsidesoldier',
            'email'      => 'lightwalker@rebels.com',
            'password'   => Hash::make('hesnotmydad'),
            'created_at' => new DateTime(),
            'updated_at' => new DateTime()
        ]);

        DB::table('users')->insert([
            'name'   => 'greendemon',
            'email'      => 'dancingsmallman@rebels.com',
            'password'   => Hash::make('yodaIam'),
            'created_at' => new DateTime(),
            'updated_at' => new DateTime()
        ]);

    }
}



