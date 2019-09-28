<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Item;
use App\Checklist;

class Template extends Model 
{
    protected $table = 'templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
    
    public function checklist()
    {
        return $this->hasOne(Checklist::class, 'assign_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
