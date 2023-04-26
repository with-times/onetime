<?php

namespace App\Services\Web;

use App\Models\Web\Deed;
use App\Models\Web\Subscribe;
use App\Models\Web\WebSite;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class WebService
{
    protected FeedService $webFeedService;

    public function __construct(FeedService $service)
    {
        $this->webFeedService = $service;
    }

    /**
     *
     * @fun create
     * @param $data
     * @return mixed
     * @date 2023/4/15
     * @throws ValidationException
     * @author 刘铭熙
     */
    public function create($data): mixed
    {
        $this->validator($data);

        $webSite = new WebSite([
            'title' => $data['title'],
            'url' => $data['url'],
            'feed' => $data['feedUrl'] ?? null,
            'site_message' => $data['description']
        ]);

        return auth()->user()->websites()->save($webSite);
    }

    /**
     *
     * @fun update
     * @param $data
     * @param $webId
     * @return mixed
     * @date 2023/4/15
     * @throws ValidationException
     * @author 刘铭熙
     */
    public function update($data, $webId): mixed
    {
        $this->validator($data, $webId);

        $webSite = WebSite::findOrFail($webId);

        $res = $webSite->update([
            'title' => $data['title'],
            'url' => $data['url'],
            'feed' => $data['feedUrl'],
            'site_message' => $data['description'],
        ]);

        return WebSite::findOrFail($webId);
    }

    /**
     *
     * @fun delete
     * @param $id
     * @date 2023/4/15
     * @author 刘铭熙
     */
    public function delete($id): void
    {
        $website = WebSite::findOrFail($id);
        $website->delete();
    }

    /**
     *
     * @fun getAll
     * @param array $data
     * @return LengthAwarePaginator
     * @date 2023/4/15
     * @author 刘铭熙
     */
    public function getAll(array $data = []): LengthAwarePaginator
    {
        $query = WebSite::query();

        if (isset($data['status'])) {
            if ($data['status'] === 'active') {
                $query->where('state', (string)\App\Enums\WebSite::NORMAL);
            } elseif ($data['status'] === 'inactive') {
                $query->where('state', (string)\App\Enums\WebSite::WEB_ERROR);
            }
        }
        if (isset($data['user_id'])) {
            $query->where('user_id', $data['user_id']);
        }

        // 根据时间进行筛选
        if (isset($data['orderBy'])) {
            if ($data['orderBy'] === 'true') {
                $query->orderBy('created_at');
            } else {
                $query->orderBy('created_at', 'desc');
            }
        }
        $query->with('user');
        // 分页
        $perPage = $data['per_page'] ?? 10;
        $page = $data['page'] ?? 1;
        return $query->paginate($perPage, ['*'], 'page', $page);


    }

    /**
     *
     * @fun getDeeds
     * @date 2023/4/19
     * @return \Illuminate\Database\Eloquent\Collection|array
     * @author 刘铭熙
     */
    public function getDeeds(): \Illuminate\Database\Eloquent\Collection|array
    {
        $deed = Deed::query();
        $deed->where('user_id', auth()->id());
        $deed->take(20)->orderBy('created_at', 'desc');

        return $deed->get();
    }

    /**
     *
     * @fun numByWeb
     * @param array $data
     * @return Model|Builder|null
     * @date 2023/4/23
     * @throws ValidationException
     * @author 刘铭熙
     */
    public function numByWeb(array $data): Model|Builder|null
    {
        $validator = Validator::make($data, [
            'number' => [
                'required',
                'string'
            ]
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $webs = WebSite::query();
        $webs->with('user');
        $webs->with('deeds');
        return $webs->where('site_number', $data['number'])->first();
    }

    /**
     *
     * @fun getWebInfo
     * @param $data
     * @return Collection
     * @date 2023/4/19
     * @throws ValidationException
     * @author 刘铭熙
     */
    public function getWebInfo($data): Collection
    {
        $validator = Validator::make($data, [
            'webUrl' => [
                'required',
                'url'
            ],
        ], [
            'webUrl.required' => 'feed订阅地址不能为空!',
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }


        $webFeed = $this->webFeedService->getWebFeed($data['webUrl'])->getFeed();


        return collect([
            'title' => $webFeed->getTitle(),
            'url' => $webFeed->getLink(),
            'description' => $webFeed->getDescription(),
            'feed' => $data['webUrl']
        ]);


    }

    public function getAllFeed(array $data): LengthAwarePaginator
    {
        $query = Subscribe::query();

        // 根据时间进行筛选
        if (isset($data['orderBy'])) {
            if ($data['orderBy'] === 'true') {
                $query->orderBy('last_modified');
            } else {
                $query->orderBy('last_modified', 'desc');
            }
        }
        $query->with('user');
        $query->with('website');
        // 分页
        $perPage = $data['per_page'] ?? 10;
        $page = $data['page'] ?? 1;
        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     *
     * @fun search
     * @param mixed $search
     * @return Collection
     * @date 2023/4/24
     * @author 刘铭熙
     */
    public function search(mixed $search): Collection
    {
        $subscribe = Subscribe::search($search)->get();
        $website = WebSite::search($search)->get();
        $website->load('subscribes');
        return collect([
            'websites' => $website,
            'subscribes' => $subscribe
        ]);
    }


    /**
     *
     * @fun validator
     * @param $data
     * @param null $webId
     * @date 2023/4/15
     * @throws ValidationException
     * @author 刘铭熙
     */
    protected function validator($data, $webId = null): void
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'url' => [
                'required',
                'url',
                Rule::unique('web_sites', 'url')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                })->ignore($webId),
            ],
            'feedUrl' => [
                'nullable',
                'url',
                Rule::unique('web_sites', 'feed')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                })->ignore($webId),
            ],
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }


}
