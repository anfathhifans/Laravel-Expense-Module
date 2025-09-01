<?php

namespace Modules\Expenses\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ExpenseCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
        ];
    }
}
