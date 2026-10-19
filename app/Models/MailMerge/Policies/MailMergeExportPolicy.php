<?php

namespace App\Models\MailMerge\Policies;

use App\Models\MailMerge\MailMergeExport;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MailMergeExportPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isAdministrator()) {
            return true;
        }
        foreach ($user->roles as $role) {
            if ($role->name == 'MailMergeAdmin') {
                return true;
            }
        }
    }

    /**
     * Determine whether the user can view the export.
     *
     * @return mixed
     */
    public function view(User $user, MailMergeExport $export)
    {
        return $export->user_id === $user->id;
    }
}
