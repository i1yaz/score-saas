<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\ParentUser;
use App\Models\Student;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class InvoicePolicy
{
    use HandlesAuthorization;
    //Before method is inside the AuthServiceProvider

    public function viewAny(Authenticatable $user): bool
    {
        if ($user->hasRole(['parent', 'student'])) {
            return true;
        }
    }

    public function view(Authenticatable $user, Invoice $invoice): bool
    {
        if ($user->hasRole(['parent']) && $user instanceof ParentUser) {
            return $invoice->parent_id == $user->id;
        }
        if ($user->hasRole(['student']) && $user instanceof Student) {
            return $invoice->student_id == $user->id;
        }
        return false;
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
