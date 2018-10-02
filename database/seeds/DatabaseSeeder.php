<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Thread', 30)->create();
        App\Thread::all()->each(function($t) {
            for ($i=0;$i<20;$i++)
            {
                $user = App\User::inRandomOrder()->first();
                factory('App\Reply')->create(['user_id' => $user->id, 'thread_id' => $t->id]);
            }
        });
    }
}
