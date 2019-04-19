<?php


namespace Demands\Domain;

use Users\Domain\User;

class StatusResolver
{
    public function resolveStatusWhenDemandIsAccepted(Demand $demand, User $user): int
    {
        if ($user->isDepartmentManager() && $demand->isUntouched()) {
            return Demand::STATUS_ASSIGNED_BY_DEPARTMENT_MANAGER;
        } elseif ($user->isTeacher()) {
            return Demand::STATUS_ACCEPTED_BY_TEACHER;
        } elseif ($user->isDepartmentManager()) {
            return Demand::STATUS_ACCEPTED_BY_DEPARTMENT_MANAGER;
        } elseif ($user->isInstituteDirector()) {
            return Demand::STATUS_ACCEPTED_BY_INSTITUTE_DIRECTOR;
        } elseif ($user->isDean()) {
            return Demand::STATUS_ACCEPTED_BY_DEAN;
        } elseif ($user->isPlanner() && $demand->isAcceptedByDean()) {
            return Demand::STATUS_EXPORTED;
        }
    }

    public function resolveStatusesForDemandListing(User $user)
    {
        if ($user->isDepartmentManager()) {
            return [Demand::STATUS_ACCEPTED_BY_TEACHER, Demand::DECLINED_BY_TEACHER, Demand::STATUS_UNTOUCHED];
        } elseif ($user->isInstituteDirector()) {
            return [Demand::STATUS_ACCEPTED_BY_DEPARTMENT_MANAGER];
        } elseif ($user->isDean()) {
            return [Demand::STATUS_ACCEPTED_BY_INSTITUTE_DIRECTOR];
        } elseif ($user->isPlanner()) {
            return [Demand::STATUS_ACCEPTED_BY_DEAN];
        }

        var_dump($user->getRoles());die;
    }
}
