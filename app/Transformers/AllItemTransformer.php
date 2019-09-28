<?php

namespace App\Transformers;

use App\Checklist;
use League\Fractal;
use App\Item;

class AllItemTransformer extends Fractal\TransformerAbstract
{    
    public $type = 'items';

    public function transform(Item $items)
    {
        return [
            'id' => $items->id,
            'description' => $items->description,
            'is_completed' => $items->is_completed,
            'completed_at' => $items->completed_at,
            'completed_by' => $items->completed_by,
            'due' => $this->formatDate($items->due),
            'urgency' => $items->urgency,
            'updated_by' => $items->updated_by,
            'created_by' => $items->created_by,
            'checklist_id' => $items->checklist_id,
            'assignee_id' => $items->assignee_id,
            'task_id' => $items->task_id,
            'deleted_at' => $items->deleted_at,
            'updated_at' => $this->formatDate($items->updated_at),
            'created_at' => $this->formatDate($items->created_at)
        ];
    }

    protected function formatDate($date)
    {
        if (is_null($date)) {
            return null;
        }

        return date("Y-m-d\TH:i:sP", strtotime($date));
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
}