<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Web\WebSite;
use App\Services\Web\FeedService;
use App\Services\Web\WebService;
use FeedIo\Adapter\Http\Client;
use FeedIo\FeedIo;
use Illuminate\Http\Request;
use Psr\Log\NullLogger;

class IndexController extends Controller
{
    public function index()
    {
//        https://sady0.com/feed/
//        $client = new Client(new \GuzzleHttp\Client([
//            'verify' => false
//        ]));
//        $feedIo = new FeedIo($client, new NullLogger());
//
//        $feeds = $feedIo->read('https://sady0.com/feed/');
//
//        foreach ($feeds->getFeed() as $item){
//            dd($item);
//        }

//        $data = [
//            'status' => 'active'
//        ];
//        $web = app(WebService::class);
//        dd($web->getAll($data));

        /**
         *   #categories: ArrayIterator {#382 ▶}
        #author: FeedIo\Feed\Item\Author {#403 ▶}
        #lastModified: DateTime @1676552181 {#401 ▶}
        #title: "如何学会优雅实用的 Markdown语法写作"
        #publicId: "https://sady0.com/?p=1090"
        #link: "https://sady0.com/1090/"
        #host: "//sady0.com"
        #elements: ArrayIterator {#381 ▶}
        #medias: ArrayIterator {#378 ▶}
        #summary: null
        #content: "在数字化时代，写作已经成为人们日常生活中不可或缺的一部分。而要写好一篇文章，排版和格式也是至关重要的。对于需要 [&#8230;]"
         */
        $feed = app(FeedService::class);
        $web = WebSite::query()->find(2);
        $a = $feed->createFeedItem($web);
        return response()->json($a);


    }
}
