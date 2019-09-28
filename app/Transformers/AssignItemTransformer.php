<?php

namespace App\Transformers;

use App\Checklist;
use League\Fractal;
use App\Item;

class AssignItemTransformer extends Fractal\TransformerAbstract
{    
    protected $tmp;

    public $type = 'items';

    public function transform(Item $checklists)
    {
        return [
            'id' => $checklists->id,
            'description' => $checklists->description,
            'is_completed' => $checklists->is_completed,
            'completed_at' => $checklists->completed_at,
            'due' => $this->formatDate($checklists->due),
            'urgency' => $checklists->urgency,
            'updated_by' => $checklists->updated_by,
            'user_id' => $checklists->created_by,
            'checklist_id' => $checklists->checklist_id,
            'deleted_at' => $this->formatDate($checklists->deleted_at),
            'created_at' => $this->formatDate($checklists->created_at),
            'updated_at' => $this->formatDate($checklists->updated_at),
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