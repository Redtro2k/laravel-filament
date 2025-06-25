<?php

namespace App\Policies;

use App\Models\Supplier;
use App\Models\User;

class SupplierPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_supplier');
    }

    public function view(User $user, Supplier $supplier): bool
    {
        return $user->can('view_supplier');
    }

    public function create(User $user): bool
    {
        return $user->can('create_supplier');
    }

    public function update(User $user, Supplier $supplier): bool
    {
        return $user->can('update_supplier');
    }

    public function delete(User $user, Supplier $supplier): bool
    {
        return $user->can('delete_supplier');
    }

    public function restore(User $user, Supplier $supplier): bool
    {
        return $user->can('restore_supplier');
    }

    public function forceDelete(User $user, Supplier $supplier): bool
    {
        return $user->can('force_delete_supplier');
    }

        public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_supplier');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_supplier');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_supplier');
    }

    public function replicate(User $user, Supplier $supplier): bool
    {
        return $user->can('replicate_supplier');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder_supplier');
    }
}
