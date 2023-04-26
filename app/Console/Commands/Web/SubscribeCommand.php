<?php

namespace App\Console\Commands\Web;

use App\Jobs\Web\GetFeed;
use App\Models\Web\WebSite;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SubscribeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getFeed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl feed subscriptions on a regular basis（定时抓取feed订阅）';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        // 获取所有 feed 字段不为空的网站
        $websites = Website::query()->whereNotNull('feed')->where('state', '0')->get();

        // 遍历所有网站，并生成队列任务获取 feed 订阅
        foreach ($websites as $website) {
            Log::info("任务开始{$website->title}");
            dispatch(new GetFeed($website));
            $this->info("任务开始{$website->title}");
        }

    }
}
