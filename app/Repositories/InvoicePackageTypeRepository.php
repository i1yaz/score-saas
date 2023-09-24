<?php

namespace App\Repositories;

use App\Models\InvoicePackageType;
use App\Repositories\BaseRepository;

class InvoicePackageTypeRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name'
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
