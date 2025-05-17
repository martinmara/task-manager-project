<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'description'];

    // public function members()
    // {
    //     return $this->hasMany(User::class); 
    // }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function user()
{
    return $this->belongsTo(User::class);
}
public function members()
{
    return $this->belongsToMany(User::class, 'team_user'); // assuming a pivot table named team_user
}
public function projects()
{
    return $this->hasMany(Project::class);
}
  public function users()
    {
        return $this->belongsToMany(User::class, 'team_user');
    }


}
