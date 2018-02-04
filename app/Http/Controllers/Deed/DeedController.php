<?php

namespace Cemal\Http\Controllers\Deed;

use Illuminate\Http\Request;
use Cemal\Http\Controllers\Controller;
use Cemal\Services\DeedService;
use Cemal\Models\Deed;

class DeedController extends Controller
{
    private $deedService;

    public function __construct(
        DeedService $deedService
    ) {
        $this->deedService = $deedService;
    }

    /**
     * @SWG\Post(
     *     path="/deeds",
     *     summary="create deed",
     *     @SWG\Parameter( name="body", in="body", required=true, @SWG\Schema(ref="#/definitions/Deed") ),
     *     @SWG\Response(response="200", description="ok"),
     *     @SWG\Response(response="400", description="bad input")
     * )
     */
    public function create(Request $request)
    {
        try {
            $this->validatePrivilege('create', Deed::class);

            $deed = $this->deedService->create($request->all());

            return $this->response($deed, 201, null);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    /**
     * @SWG\Get(
     *     path="/deeds",
     *     summary="deed lists",
     *     @SWG\Response(response="200", description="ok"),
     *     @SWG\Response(response="400", description="bad input")
     * )
     */
    public function all(Request $request){
    	$limit = $request->query->get('limit') ?: 20;
        $offset = $request->query->get('offset') ?: 0;
        $canSeeAll = $this->isGranted('lists', Deed::class);

        $params = array();

        if (!$canSeeAll){
        	$params['user_id'] = \Auth::user()->id;
        	$params['include_public'] = true;
        }

    	try {

            $deeds = $this->deedService->all($params, [['title' => 'ASC']], $limit, $offset);
            $total = $this->deedService->count($params);

            return $this->response($deeds, 200, null, ['total' => $total]);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }
}
