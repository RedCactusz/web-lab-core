<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class CrudService
{
    /**
     * @param  class-string<JsonResource>|null  $resourceClass
     */
    public function paginated(LengthAwarePaginator $paginator, ?string $resourceClass = null): JsonResponse
    {
        $items = $paginator->items();

        return response()->json([
            'data' => $resourceClass !== null
                ? $resourceClass::collection($items)
                : $items,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    /**
     * @param  class-string<Model>  $modelClass
     */
    public function store(string $modelClass, array $data): Model
    {
        return $modelClass::create($data);
    }

    public function update(Model $model, array $data): Model
    {
        $model->update($data);

        return $model;
    }

    public function destroy(Model $model): void
    {
        $model->delete();
    }
}
