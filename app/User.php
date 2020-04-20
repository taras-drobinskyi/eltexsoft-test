<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'first_name', 'last_name', 'email', 'password', 'status', 'date_of_birth', 'notes', 'last_login', 'avatar', 'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token', 'role_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function canChangeStatus(User $currentUser, User $userForChange)
    {
        if ($userForChange && $currentUser->id !== $userForChange->id) {
            $canChange = true;
        }
        if ($userForChange->hasRole('admin') && count(User::role('admin')->get()) > 1) {
            $canChange = true;
        } else {
            $canChange = false;
        }
        return $canChange;
    }
}
