<?php

namespace App\Observers;

use App\Models\Web\Deed;
use App\Models\Web\WebSite;
use App\Services\Util\UtilService;
use App\Services\Web\DeedService;
use Illuminate\Support\Str;

class WebsiteObserver
{
    protected UtilService $utilService;
    protected DeedService $deedService;

    public function __construct(UtilService $utilService, DeedService $deedService)
    {
        $this->utilService = $utilService;
        $this->deedService = $deedService;
    }

    /**
     * Handle the WebSite "created" event.
     *
     * @param WebSite $webSite
     * @return void
     */
    public function created(WebSite $webSite): void
    {

        $this->deedService->create(
            'created',
            "'{$webSite->title}'正式加入与时同行，编号为{$webSite->number()}",
            $webSite
        );
    }

    /**
     * Handle the WebSite "updated" event.
     *
     * @param WebSite $webSite
     * @return void
     */
    public function updated(WebSite $webSite): void
    {
        $this->deedService->create(
            'updated',
            "修改网站：'{$webSite->title}'",
            $webSite
        );
    }

    /**
     * Handle the WebSite "deleted" event.
     *
     * @param WebSite $webSite
     * @return void
     */
    public function deleted(WebSite $webSite)
    {
        //
    }

    /**
     * Handle the WebSite "restored" event.
     *
     * @param WebSite $webSite
     * @return void
     */
    public function restored(WebSite $webSite)
    {
        //
    }

    /**
     * Handle the WebSite "force deleted" event.
     *
     * @param WebSite $webSite
     * @return void
     */
    public function forceDeleted(WebSite $webSite)
    {
        //
    }

    public function creating(WebSite $website): void
    {
       $this->utilService->webCreated($website);
    }

}
