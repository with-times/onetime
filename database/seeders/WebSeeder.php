<?php

namespace Database\Seeders;

use App\Models\Web\WebSite;
use App\Services\Web\WebService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WebSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($a=1;$a<100;$a++){
            $data = [
                'title' => Str::random(3),
                'url' => 'https://'.Str::random(3).'.re',
                'feedUrl' => 'https://'.Str::random(3).'.re/feed',
                'description' => '平凡的世界，灼热的心不止'
            ];
            $webSite = app(WebService::class);
            auth()->loginUsingId(17);
            $webSite->create($data);
        }


    }
}
