<?php

use App\Models\InvoicePackageType;
use Database\Seeders\DatabaseSeeder;

it('has invoice packages seeded',function (){
    $this->seed(DatabaseSeeder::class);
    expect(InvoicePackageType::count())->toBe(5);
});
it('has completion codes seeded',function (){
    $this->seed(DatabaseSeeder::class);
    expect(\App\Models\ListData::where('list_id',1)->count())->toBe(5);
});
