<?php

namespace App\Repositories;

use App\Models\InvoicePackageType;

class InvoicePackageTypeRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return InvoicePackageType::class;
    }
}
