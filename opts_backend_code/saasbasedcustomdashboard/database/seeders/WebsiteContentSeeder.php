<?php

namespace Database\Seeders;

use App\Enums\WebsitePageTypeEnum;
use App\Models\WebsiteContent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WebsiteContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (WebsitePageTypeEnum::cases() as $shape) {
            if ($shape === WebsitePageTypeEnum::FAQ) {
                for ($i = 0; $i < 5; $i++) {
                    WebsiteContent::create([
                        'page_name' => $shape,
                        'question' => Str::random(60),
                        'content' => Str::random(60)
                    ]);
                }
            } else {
                WebsiteContent::create([
                    'page_name' => $shape,
                    'content' => Str::random(60)
                ]);
            }
        }
    }
}
