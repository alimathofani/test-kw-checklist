<?php

namespace App\Transformers;

use App\Checklist;
use League\Fractal;
use App\Item;

class ItemTransformer extends Fractal\TransformerAbstract
{    
    protected $tmp;

    public $type = 'checklists';

    public function __construct($tmp = null)
    {
        $this->tmp = $tmp;
    }

    public function transform(Checklist $checklists)
    {
        switch ($this->tmp) {
            case 'all':
                return $this->allTemplate($checklists);
                break;
            
            case 'allChecked':
                return $this->allCheckedTemplate($checklists);
                break;
            
            case 'show':
                return $this->showTemplate($checklists);
                break;
            
            case 'create':
                return $this->createTemplate($checklists);
                break;
            
            case 'update':
                return $this->updateTemplate($checklists);
                break;

            default:
                return $this->defaultTemplate($checklists);
                break;
        }
    }

    public function allTemplate($checklists)
    {
        return [
            'id' => $checklists->id,
            'description' => $checklists->description,
            'is_completed' => $checklists->is_completed,
            'completed_at' => $checklists->completed_at,
            'completed_by' => $checklists->completed_by,
            'due' => $this->formatDate($checklists->due),
            'urgency' => $checklists->urgency,
            'update_by' => $checklists->updated_by,
            'created_by' => $checklists->created_by,
            'checklist_id' => $checklists->checklist_id,
            'assignee_id' => $checklists->assignee_id,
            'task_id' => $checklists->task_id,
            'deleted_at' => $this->formatDate($checklists->deleted_at),
            'updated_at' => $this->formatDate($checklists->updated_at),
            'created_at' => $this->formatDate($checklists->created_at)
        ];
    }

    public function allCheckedTemplate($checklists)
    {
        return [
            'id' => $checklists->id,
            'object_domain' => $checklists->object_domain, 
            'object_id' => $checklists->object_id,
            'description' => $checklists->description,
            'is_completed' => $checklists->is_completed,
            'due' => $this->formatDate($checklists->due),
            'urgency' => $checklists->urgency,
            'completed_at' => $this->formatDate($checklists->completed_at),
            'last_update_by' => $checklists->updated_by,
            'update_at' => $this->formatDate($checklists->update_at),
            'created_at' => $this->formatDate($checklists->created_at),
            'items' => collect($checklists->items)->map(function ($item, $key) {
                $set = [
                    'id' => $item->id,
                    'name' => $item->description,
                    'user_id' => $item->created_by,
                    'is_completed' => $item->is_completed,
                    'due' => $this->formatDate($item->due),
                    'urgency' => $item->urgency,
                    'checklist_id' => $item->checklist_id,
                    'assignee_id' => $item->assignee_id,
                    'task_id' => $item->task_id,
                    'completed_at' => $this->formatDate($item->completed_at),
                    'last_update_by' => $item->update_by,
                    'update_at' => $this->formatDate($item->update_at),
                    'created_at' => $this->formatDate($item->created_at)
                ];
                return $set;
            })
        ];
    }

    public function showTemplate($checklists)
    {
        return [
            'id' => $checklists->id,
            'description' => $checklists->items->first()->description,
            'is_completed' => $checklists->items->first()->is_completed,
            'completed_at' => $checklists->items->first()->completed_at,
            'due' => $this->formatDate($checklists->items->first()->due),
            'urgency' => $checklists->items->first()->urgency,
            'update_by' => $checklists->items->first()->updated_by,
            'created_by' => $checklists->items->first()->created_by,
            'checklist_id' => $checklists->items->first()->checklist_id,
            'assignee_id' => $checklists->items->first()->assignee_id,
            'task_id' => $checklists->items->first()->task_id,
            'deleted_at' => $checklists->items->first()->deleted_at,
            'updated_at' => $this->formatDate($checklists->items->first()->updated_at),
            'created_at' => $this->formatDate($checklists->items->first()->created_at)
        ];
    }

    public function createTemplate($checklists)
    {
        return [
            'id' => $checklists->id,
            'description' => $checklists->description,
            'is_completed' => $checklists->is_completed,
            'completed_at' => $checklists->completed_at,
            'due' => $this->formatDate($checklists->due),
            'urgency' => $checklists->urgency,
            'updated_by' => $checklists->updated_by,
            'updated_at' => $this->formatDate($checklists->update_at),
            'created_at' => $this->formatDate($checklists->created_at)
        ];
    }

    public function updateTemplate($checklists)
    {
        return [
            'id' => $checklists->id,
            'description' => $checklists->description,
            'is_completed' => $checklists->is_completed,
            'due' => $this->formatDate($checklists->due),
            'urgency' => $checklists->urgency,
            'assignee_id' => $checklists->items->first()->assignee_id,
            'completed_at' => $checklists->completed_at,
            'updated_by' => $checklists->updated_by,
            'updated_at' => $this->formatDate($checklists->update_at),
            'created_at' => $this->formatDate($checklists->created_at)
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