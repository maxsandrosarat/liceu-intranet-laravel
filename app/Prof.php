<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Prof extends Authenticatable
{
    use Notifiable;
    protected $guard = 'prof';
    
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    function disciplinas(){
        return $this->belongsToMany("App\Disciplina", "prof_disciplinas");
    }
}
