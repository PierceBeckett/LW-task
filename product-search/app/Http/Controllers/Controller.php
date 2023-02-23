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

    /**
     * List all the entities
     *
     * Provides filtering via request parameters
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        $request->validate([
            'ram_id'        => 'sometimes|exists:ram,id',
            'hdd_id'        => 'sometimes|exists:hdd,id',
            'location_id'   => 'sometimes|exists:locations,id',
            'storage_min'   => 'sometimes|numeric|lt:storage_max',
            'storage_max'   => 'sometimes|numeric|gt:storage_min',
        ]);

        $query = $this->model->query();
        if ($request['ram_id']) {
            $query->where('ram_id', $request['ram_id']);
        }
        if ($request['hdd_id']) {
            $query->where('hdd_id', $request['hdd_id']);
        }
        if ($request['location_id']) {
            $query->where('location_id', $request['location_id']);
        }
        if ($request['storage_min'] && $request['storage_max']) {
            $query->whereBetween('storage', [$request['storage_min'], $request['storage_max']]);
        } else {
            if ($request['storage_min']) {
                $query->where('storage', '>', $request['storage_min']);
            }
            if ($request['storage_min']) {
                $query->where('storage', '<', $request['storage_max']);
            }
        }

        return new JsonResponse(
            $query->get()
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
