<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        //關閉外鍵約束
        Schema::disableForeignKeyConstraints();

        Todo::truncate();
        User::truncate();

        User::factory(5)->create();
        Todo::factory(10000)->create();

        //啟動外鍵約束
        Schema::enableForeignKeyConstraints();
    }
}
