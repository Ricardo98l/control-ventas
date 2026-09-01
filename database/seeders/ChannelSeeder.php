<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Channel;

class ChannelSeeder extends Seeder
{
    public function run(): void
    {
        $canales = [
            'Canal moderno',
            'Canal horeca',
            'Canal tradicional',
            'Canal mayorista'
        ];

        foreach ($canales as $canal) {
            Channel::create(['name' => $canal]);
        }
    }
}