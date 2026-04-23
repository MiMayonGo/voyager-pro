<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
{
    public function view(User $user, Invoice $invoice): bool
    {
        return $invoice->booking->user_id === $user->id
            || $user->hasPermissionTo('payments.view');
    }

    public function download(User $user, Invoice $invoice): bool
    {
        return $invoice->booking->user_id === $user->id
            || $user->hasPermissionTo('payments.view');
    }
}