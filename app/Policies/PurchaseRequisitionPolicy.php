<?php
namespace App\Policies;

use App\Models\User;
use App\Models\PurchaseRequisition;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchaseRequisitionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_purchase::requisition');
    }

    public function view(User $user, PurchaseRequisition $requisition): bool
    {
        return $user->can('view_purchase::requisition');
    }

    public function create(User $user): bool
    {
        return $user->can('create_purchase::requisition');
    }

    public function update(User $user, PurchaseRequisition $requisition): bool
    {
        return $user->can('update_purchase::requisition');
    }

    public function delete(User $user, PurchaseRequisition $requisition): bool
    {
        return $user->can('delete_purchase::requisition');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_purchase::requisition');
    }

    public function forceDelete(User $user, PurchaseRequisition $requisition): bool
    {
        return $user->can('force_delete_purchase::requisition');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_purchase::requisition');
    }

    public function restore(User $user, PurchaseRequisition $requisition): bool
    {
        return $user->can('restore_purchase::requisition');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_purchase::requisition');
    }

    public function replicate(User $user, PurchaseRequisition $requisition): bool
    {
        return $user->can('replicate_purchase::requisition');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder_purchase::requisition');
    }
}
