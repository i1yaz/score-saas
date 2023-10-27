<?php

namespace App\Repositories;

use App\Models\LineItem;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LineItemRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'price'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return LineItem::class;
    }
    public function create(array $input): Model
    {
        $input['auth_guard'] = Auth::guard()->name;
        $input['added_by'] = Auth::id();
        $model = $this->model->newInstance($input);

        $model->save();

        return $model;
    }
}
