<?php

namespace App\Services\Util;

use App\Models\Web\Subscribe;
use App\Models\Web\WebSite;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class UtilService
{
    /**
     *
     * @fun webCreate
     * @param WebSite $webSite
     * @date 2023/4/23
     * @author 刘铭熙
     */
    public function webCreated(WebSite $webSite): void
    {
        $uuid = Str::uuid()->toString();
        $webSite->site_number = Str::upper(Str::random(3)) . substr($uuid, 0, 6);
        $webSite->uuid = $uuid;
    }

    public function webUpdateLastModified(Subscribe $subscribe): void
    {
        $subscribe->website()->update([
            'last_updated_at' => Carbon::parse($subscribe->last_modified),
        ]);
    }

    public function webFeedCreating(Subscribe $subscribe): void
    {
        $uuid = Str::uuid()->toString();

        $subscribe->uuid = $uuid;
    }
}
