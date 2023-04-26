<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Web\WebService;
use FeedIo\Reader\ReadErrorException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class WebController extends Controller
{
    protected WebService $webService;

    public function __construct(WebService $webService)
    {
        $this->webService = $webService;
    }

    /**
     *
     * @fun create
     * @param Request $request
     * @return JsonResponse
     * @date 2023/4/15
     * @author 刘铭熙
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $webSite = $this->webService->create($request->all());
            return $this->json([
                'website' => $webSite
            ]);
        } catch (ValidationException $e) {
            return $this->error($e);
        }
    }

    /**
     *
     * @fun update
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @date 2023/4/15
     * @author 刘铭熙
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $webSite = $this->webService->update($request->all(), $id);
            return $this->json([
                'website' => $webSite
            ]);
        } catch (ValidationException $e) {
            return $this->error($e);
        }
    }

    public function delete($id): JsonResponse
    {
        $this->webService->delete($id);
        return $this->json([], '已删除网站');
    }

    /**
     *
     * @fun getWebInfo
     * @param Request $request
     * @return JsonResponse
     * @date 2023/4/19
     * @author 刘铭熙
     */
    public function getWebInfo(Request $request): JsonResponse
    {
        try {
            $webInfo = $this->webService->getWebInfo($request->all());
            return $this->json($webInfo->toArray());
        } catch (\Exception|ReadErrorException $e) {
            return $this->error($e);
        }
    }

    /**
     *
     * @fun getAll
     * @param Request $request
     * @return JsonResponse
     * @date 2023/4/15
     * @author 刘铭熙
     */
    public function getAll(Request $request): JsonResponse
    {
        $webSite = $this->webService->getAll($request->all());

        return $this->json($webSite);
    }

    public function getAllFeed(Request $request): JsonResponse
    {
        $feed = $this->webService->getAllFeed($request->all());

        return $this->json($feed);
    }

    /**
     *
     * @fun getDeeds
     * @date 2023/4/19
     * @author 刘铭熙
     */
    public function getDeeds(): JsonResponse
    {
        return $this->json($this->webService->getDeeds());
    }

    /**
     *
     * @fun search
     * @param Request $request
     * @return JsonResponse
     * @date 2023/4/24
     * @author 刘铭熙
     */
    public function search(Request $request): JsonResponse
    {
        $res = $this->webService->search($request->input('search'));
        return $this->json($res);
    }

    /**
     *
     * @fun getWebByNum
     * @param Request $request
     * @return JsonResponse
     * @date 2023/4/23
     * @author 刘铭熙
     */
    public function getWebByNum(Request $request): JsonResponse
    {
        $data = [
            'number' => $request->route('num')
        ];
        try {
            $web = $this->webService->numByWeb($data);
            return $this->json($web);
        } catch (ValidationException $e) {
            $this->error($e);
        }
    }
}
