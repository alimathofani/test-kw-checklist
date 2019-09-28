<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class AssignTemplateTransformer extends TransformerAbstract
{
    public $type = 'checklist';
    
    protected $defaultIncludes = ['items'];

    public function transform($checklists)
    {
        return [
            'id' => $checklists->id,
            'object_domain' => $checklists->object_domain, 
            'object_id' => $checklists->object_id,
            'description' => $checklists->description,
            'is_completed' => $checklists->is_completed,
            'due' => $this->formatDate($checklists->due),
            'urgency' => $checklists->urgency,
            'completed_at' => $checklists->completed_at,
            'updated_by' => $checklists->updated_by,
            'created_by' => $checklists->created_by,
            'updated_at' => $this->formatDate($checklists->updated_at),
            'created_at' => $this->formatDate($checklists->created_at)
        ];
    }

    public function includeItems($checklists)
    {
        // dd($checklists);
        return $this->collection($checklists->items, new AssignItemTransformer(), 'items');
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

    protected function formatDate($date)
    {
        if (is_null($date)) {
            return null;
        }

        return date("Y-m-d\TH:i:sP", strtotime($date));
    }
}