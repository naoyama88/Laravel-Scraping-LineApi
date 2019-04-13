<?php

use Illuminate\Database\Seeder;

class RegisteredUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('registered_users')->insert([
            [
                'email' => getenv("EMAIL_SAMPLE_01"),
                'name' => 'sample_user_01',
                'email_cycle_status' => '1',
            ],
            [
                'email' => getenv("EMAIL_SAMPLE_01"),
                'name' => 'sample_user_02',
                'email_cycle_status' => '2',
            ],
            [
                'email' => getenv("EMAIL_SAMPLE_01"),
                'name' => 'sample_user_03',
                'email_cycle_status' => '3',
            ]
        ]);
    }
}
