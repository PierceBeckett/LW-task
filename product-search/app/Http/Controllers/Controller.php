<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

/**
 * Generic controller for all models, provides common crud operations etc
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // uses injection to ensure we have a model
    // any other controllers extend using their own relevant model
    public function __construct(private Model $model)
    {
    }

	protected $sort_by = 'value';

    /**
     * List all the entities
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        $query = $this->model->query();

        return new JsonResponse(
            $query->orderByRaw($this->sort_by)->get()
        );
    }

    /**
     * Provides details on a singular Entity specifided by its identifier
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id) : JsonResponse
    {
        return new JsonResponse($this->model->newQuery()->find($id));
    }

    /**
     * Create a new entity
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request) : JsonResponse
    {
        return new JsonResponse($this->model->create($request->all()));
    }

    /**
     * Update entity specified
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id) : JsonResponse
    {
        $ent = $this->model->query()->find($id);
        return new JsonResponse($ent->update($request->all()));
    }

    /**
     * Delete entity specified
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id) : JsonResponse
    {
        $ent = $this->model->query()->find($id);
        return new JsonResponse($ent->delete());
    }
}
