<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;

class TeamPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Team $team): bool
    {
        return $user->id === $team->owner_id || 
               $team->members->contains($user);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Team $team): bool
    {
        return $user->id === $team->owner_id || 
               $team->admins->contains($user);
    }

    public function delete(User $user, Team $team): bool
    {
        return $user->id === $team->owner_id;
    }

    public function addMember(User $user, Team $team): bool
    {
        return $user->id === $team->owner_id || 
               $team->admins->contains($user);
    }

    public function removeMember(User $user, Team $team): bool
    {
        return $user->id === $team->owner_id || 
               $team->admins->contains($user);
    }

    public function updateMemberRole(User $user, Team $team): bool
    {
        return $user->id === $team->owner_id;
    }
}
