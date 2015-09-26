<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(App\Models\User::class, 3)->create()
            ->each( function($user) {
                $user->credentials()
                    ->save(factory(App\Models\Credential::class)->make());
                }
            );
    }
}
