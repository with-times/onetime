<?php

namespace App\Jobs\Web;

use App\Models\Web\WebSite;
use App\Services\Web\FeedService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GetFeed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected WebSite $webSite;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(WebSite $webSite)
    {
        $this->onQueue('getFeed');
        $this->webSite = $webSite;
    }

    /**
     * Execute the job.
     *
     * @param FeedService $feedService
     * @return void
     */
    public function handle(FeedService $feedService): void
    {
       $feedService->createFeedItem($this->webSite);
    }
}
