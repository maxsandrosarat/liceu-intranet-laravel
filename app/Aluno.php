<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Aluno extends Authenticatable
{
    use Notifiable;
    protected $guard = 'aluno';
    
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function turma(){
        return $this->belongsTo('App\Turma');
    }

    function atividade(){
        return $this->belongsToMany("App\Atividade", "atividade_retornos");
    }
}
