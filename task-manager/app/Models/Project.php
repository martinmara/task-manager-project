<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model {
    use HasFactory;

    protected $fillable = ['name', 'description', 'team_id'];

    public function team() {
        return $this->belongsTo(Team::class);
    }

    public function tasks()
{
    return $this->hasMany(Task::class);
}
    public function users()
{
    return $this->belongsToMany(User::class);
    
}
// In Project.php
public function user()
{
    return $this->belongsTo(User::class);
}

}
