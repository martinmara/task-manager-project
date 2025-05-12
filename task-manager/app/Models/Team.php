<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name'];

    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }
}

// app/Models/TeamMember.php
class TeamMember extends Model
{
    protected $fillable = ['team_id', 'name', 'email', 'role'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
