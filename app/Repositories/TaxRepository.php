<?php

namespace App\Repositories;

use App\Models\Tax;

class TaxRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'value',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Tax::class;
    }
}
