<?php

namespace App\Transformers;

use League\Fractal;
use App\Checklist;

class ChecklistItemTransformer extends Fractal\TransformerAbstract
{
    public $type = 'checklists';

    protected $availableIncludes = ['items'];
    
    public function transform(Checklist $checklists)
    {
        return [
            'id' => $checklists->id,
            'object_domain' => $checklists->object_domain, 
            'object_id' => $checklists->object_id,
            'description' => $checklists->description,
            'is_completed' => $checklists->is_completed,
            'due' => $checklists->due,
            'urgency' => $checklists->urgency,
            'completed_at' => $checklists->completed_at,
            'last_update_by' => $checklists->updated_by,
            'updated_at' => $checklists->updated_at,
            'created_at' => $checklists->created_at,
            'items' => collect($checklists->items)->map(function ($item, $key) {
                return $item->setAppends([]);
            })
        ];
    }

    /**
     * Base URL
     *
     * @return string
     */
    public function baseUrl()
    {
        return env('APP_URL', 'http://example.com');
    }
    
    public function includeItems($checklists)
    {
        return $this->collection($checklists->items, new ItemTransformer(), 'items');
    }

}