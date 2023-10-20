<?php

use App\Models\InvoicePackageType;
use Database\Seeders\DatabaseSeeder;

it('has_invoice_packages_seeded',function (){
    $this->seed(DatabaseSeeder::class);
    dd(InvoicePackageType::all());
});
