<?php

namespace App\Policies;

use App\Models\Invoice;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;

class InvoicePolicy
{
    use HandlesAuthorization;

    public function viewAny(Authenticatable $user): bool
    {

    }

    public function view(Authenticatable $user, Invoice $invoice): bool
    {
    }

    public function create(Authenticatable $user): bool
    {
    }

    public function update(Authenticatable $user, Invoice $invoice): bool
    {
    }

    public function delete(Authenticatable $user, Invoice $invoice): bool
    {
    }

    public function restore(Authenticatable $user, Invoice $invoice): bool
    {
    }

    public function forceDelete(Authenticatable $user, Invoice $invoice): bool
    {
    }
}
