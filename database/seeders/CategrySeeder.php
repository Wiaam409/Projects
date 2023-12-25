<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\categories;

class CategrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       categories::create([
        'name' => 'oral medications',
        
       ]);

       categories::create([
        'name' => 'pharmaceuticals',
        
       ]);

       categories::create([
        'name' => 'elphant',
        
       ]);

       categories::create([
        'name' => 'the vaccines',
        
       ]);

       categories::create([
        'name' => 'external use medicines',
        
       ]);
    }
}
