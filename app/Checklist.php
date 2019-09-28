<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Item;
use App\Template;
use Carbon\Carbon;

class Checklist extends Model 
{
    protected $table = 'checklists';
    protected $appends = ['due_unit', 'due_interval'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'object_domain', 
        'object_id',
        'description',
        'due',
        'task_id',
        'urgency',
        'is_completed',
        'completed_at',
        'updated_by',
        'created_by',
        'update_at'
    ];

    public function getDueUnitAttribute()
    {
        if (is_null($this->attributes['due'])) {
            return "";
        }

        return "hour";
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
        
        return round($hour);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function templateItems()
    {
        return $this->hasMany(Item::class);
    }
    
    public function template()
    {
        return $this->belongsTo(Template::class, 'assign_id');
    }

    public function scopeSumToday($query, $date)
    {
        return $query->withCount(['items as today_count' => function($query) use ($date){
            if (!empty($date)) {
                $query->whereDate('created_at', $date);
            }
        }]);
    }

    public function scopeSumPastDue($query, $date)
    {
        return $query->withCount(['items as past_due_count' => function($query) use ($date){
            if (!empty($date)) {
                $query->where('is_completed', false)->where('due', '<=', $date);
            }
        }]);
    }

    public function scopeSumThisWeek($query, $start_date, $end_date)
    {
        return $query->withCount(['items as this_week_count' => function($query) use ($start_date, $end_date){
            $query->whereBetween('created_at', [$start_date, $end_date]);
        }]);
    }
    
    public function scopeSumPastWeek($query, $start_date, $end_date)
    {
        return $query->withCount(['items as past_week_count' => function($query) use ($start_date, $end_date){
            $query->where('is_completed', false)->whereBetween('created_at', [$start_date, $end_date]);
        }]);
    }

    public function scopeSumThisMonth($query, $month)
    {
        return $query->withCount(['items as this_month_count' => function($query) use ($month){
            $query->whereMonth('created_at', $month);
        }]);
    }

    public function scopeSumPastMonth($query, $month)
    {
        return $query->withCount(['items as past_month_count' => function($query) use ($month){
            $query->where('is_completed', false)->whereMonth('created_at', $month);
        }]);
    }

    // public function items()
    // {
    //     return $this->belongsToMany(Item::class, 'checklist_item')->withTimestamps();
    // }
}
