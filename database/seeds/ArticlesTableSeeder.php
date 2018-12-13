<?php

use Illuminate\Database\Seeder;
 

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        // name of mode | number of data to be inserted
        factory(App\Article::class,30)->create();
    }
}
