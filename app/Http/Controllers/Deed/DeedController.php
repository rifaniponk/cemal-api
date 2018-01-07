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

            return $this->response($deed, 201);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }
}
