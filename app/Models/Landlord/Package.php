<?php

namespace App\Models\Landlord;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends BaseModel
{

    protected function maxTutors(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => -1 == $value ? 'Unlimited' : $value,
        );
    }
    protected function maxStudents(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => -1 == $value ? 'Unlimited' : $value,
        );
    }
    protected function maxStudentPackages(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => -1 == $value ? 'Unlimited' : $value,
        );
    }
    protected function maxMonthlyPackages(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => -1 == $value ? 'Unlimited' : $value,
        );
    }
}
