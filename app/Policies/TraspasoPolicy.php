<?php

namespace App\Policies;

use App\User;
use App\Traspaso;
use Illuminate\Auth\Access\HandlesAuthorization;

class TraspasoPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function view(User $user, Traspaso $traspaso){

       return true;
    }
}
