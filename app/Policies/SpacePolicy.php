<?php

namespace App\Policies;

use App\Models\Space;
use App\Models\User;

class SpacePolicy
{
    /**
     * Only admins can manage spaces
     */
    public function manage(User $user): bool
    {
        return $user->is_admin === true;
    }
}
