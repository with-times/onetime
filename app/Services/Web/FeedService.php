<?php

namespace App\Services\Web;

use App\Jobs\Web\CreateFeed;
use App\Models\Web\Subscribe;
use App\Models\Web\WebSite;
use FeedIo\Adapter\Http\Client;
use FeedIo\Reader\Result;
use \GuzzleHttp\Client as GuzzleClient;
use FeedIo\FeedIo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Psr\Log\NullLogger;

class FeedService
{
    /**
     * 获取feed
     * @fun getWebFeed
     * @param string $web
     * @return Result
     * @date 2023/4/19
     * @author 刘铭熙
     */
    public function getWebFeed(string $web): Result
    {
        $client = new Client(new GuzzleClient([
            'verify' => env('FEED_VERIFY', 'false')
        ]));
        $feedIo = new FeedIo($client, new NullLogger());

        return $feedIo->read($web);
    }

    /**
     *
     * @fun createFeedItem
     * @param WebSite $webSite
     * @return Collection|null
     * @date 2023/4/23
     * @author 刘铭熙
     */
    public function createFeedItem(WebSite $webSite): ?Collection
    {
        $feed = $this->getWebFeed($webSite->getFeedUrl())->getFeed();

        $data = [];
        foreach ($feed as $item) {
//            if (Carbon::parse($item->getLastModified())->isToday()) {
                $data[] = [
                    'title' => $item->getTitle(),
                    'description' => $item->getContent(),
                    'link' => $item->getLink(),
                    'last_modified' => Carbon::parse($item->getLastModified()),
                    'authors' => $item->getAuthor()->getName()
                ];
//            }
        }
        $data = collect($data);
        if ($data->isNotEmpty()) {
            $data->each(function ($item) use ($webSite) {
                dispatch(new CreateFeed($item,$webSite));
            });
            return $data;
        } else {
            return null;
        }

    }

    /**
     *
     * @fun createItem
     * @param array $item
     * @param WebSite $webSite
     * @return Subscribe
     * @date 2023/4/23
     * @author 刘铭熙
     */
    public function createItem(array $item, WebSite $webSite): Subscribe
    {
        $subscribes = new Subscribe($item);
        $existing = Subscribe::query()->where([
            'title' => $subscribes->title,
            'description' => $subscribes->description,
            'link' => $subscribes->link,
        ])->first();
        if ($existing) {
            // 如果已存在，则更新记录
            $existing->update([
                'last_modified' => $subscribes->last_modified,
                'authors' => $subscribes->authors,
            ]);
        } else {
            $subscribes->user()->associate($webSite->user);
            $webSite->subscribes()->save($subscribes);
        }

        return $subscribes;
    }


}
