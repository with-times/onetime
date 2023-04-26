<?php

namespace App\Services\Web;

use App\Models\Web\Deed;
use App\Models\Web\WebSite;

class DeedService
{
    public function create(string $title, $content ,WebSite $webSite): void
    {
        // 创建一个新的Deed实例
        $deed = new Deed([
            'title' => $title,
            'content' => $content
        ]);

        // 将Deed与用户和新网站关联
        $deed->user()->associate($webSite->user);
        $deed->webSite()->associate($webSite);

        // 保存Deed
        $deed->save();
    }

}
