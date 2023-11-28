<?php

namespace App\Policies;

use App\Models\Invoice;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class InvoicePolicy
{
    use HandlesAuthorization;

    public function before(Authenticatable $user)
    {
        //['super-admin','admin','student','parent','tutor','proctor','client','developer']

        if (Auth::user()->hasRole(['super-admin', 'admin'])) {
            return true;
        }
        if (Auth::user()->hasRole([ 'tutor','proctor','developer'])) {
            abort(403, getRoleOfLoggedInUser() . 's do not have access to Invoices');
        }
    }

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
