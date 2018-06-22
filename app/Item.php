<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['code', 'name', 'url', 'image_url'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('type')->withTimestamps();
    }
    
//want------------------------------------------------------------------------------------
    public function want_users()
    {
        return $this->users()->where('type', 'want');
    }
    
//have------------------------------------------------------------------------------------
    public function have_users()
    {
        return $this->users()->where('type', 'have');
    }
}
