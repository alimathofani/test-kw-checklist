<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model 
{
    use SoftDeletes;

    protected $table = 'items';

    protected $dates = ['deleted_at'];
    protected $appends = ['due_unit', 'due_interval'];
    // protected $visible = ['urgency', 'due_unit', 'description', 'due_interval'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'is_completed',
        'completed_at',
        'due',
        'urgency',
        'updated_by',
        'created_by',
        'assignee_id',
        'task_id',
        'checklist_id',
        'update_at'
    ];

    public function getDueUnitAttribute()
    {
        return "minute";
    }

    public function getDueIntervalAttribute()
    {
        if (is_null($this->attributes['due'])) {
            return $this->attributes['due'];
        }
        
        date_default_timezone_set('Asia/Jakarta');
        $due  = new \DateTime($this->attributes['due']);
        $now = new \DateTime();
        $minute  = abs($due->getTimestamp() - $now->getTimestamp()) / 60;
        $hour  = abs($due->getTimestamp() - $now->getTimestamp()) / 3600;
        
        return round($minute);
    }
}
