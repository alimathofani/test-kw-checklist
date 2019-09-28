<?php

namespace App\Transformers;

use League\Fractal;
use App\Checklist;

class ChecklistTransformer extends Fractal\TransformerAbstract
{
    protected $tmp;

    public $type = 'checklists';

    protected $availableIncludes = ['items'];

    public function __construct($tmp = null)
    {
        $this->tmp = $tmp;
    }

    public function transform(Checklist $checklists)
    {
        switch ($this->tmp) {
            case 'all':
                return $this->defaultTemplate($checklists);
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
        return $this->collection($checklists->items, new GetItemTransformer(), 'items');
    }

    public function defaultTemplate(Checklist $checklists)
    {
        return [
            'id' => (int) $checklists->id,
            'object_domain' => $checklists->object_domain, 
            'object_id' => $checklists->object_id,
            'description' => $checklists->description,
            'is_completed' => $checklists->is_completed,
            'due' => $this->formatDate($checklists->due),
            'task_id' => $checklists->task_id,
            'urgency' => $checklists->urgency,
            'completed_at' => $checklists->completed_at,
            'last_update_by' => $checklists->updated_by,
            'updated_at' => $this->formatDate($checklists->updated_at),
            'created_at' => $this->formatDate($checklists->created_at)
        ];
    }

    public function showTemplate(Checklist $checklists)
    {
        return [
            'id' => (int) $checklists->id,
            'object_domain' => $checklists->object_domain, 
            'object_id' => $checklists->object_id,
            'description' => $checklists->description,
            'is_completed' => $checklists->is_completed,
            'due' => $this->formatDate($checklists->due),
            'urgency' => $checklists->urgency,
            'completed_at' => $checklists->completed_at,
            'last_update_by' => $checklists->updated_by,
            'updated_at' => $this->formatDate($checklists->updated_at),
            'created_at' => $this->formatDate($checklists->created_at)
        ];
    }

    public function createTemplate(Checklist $checklists)
    {
        return [
            'id' => (int) $checklists->id,
            'object_domain' => $checklists->object_domain, 
            'object_id' => $checklists->object_id,
            'task_id' => $checklists->task_id,
            'description' => $checklists->description,
            'is_completed' => $checklists->is_completed,
            'due' => $this->formatDate($checklists->due),
            'urgency' => $checklists->urgency,
            'completed_at' => $checklists->completed_at,
            'last_update_by' => $checklists->updated_by,
            'created_by' => $checklists->created_by,
            'updated_at' => $this->formatDate($checklists->updated_at),
            'created_at' => $this->formatDate($checklists->created_at)
        ];
    }

    public function updateTemplate(Checklist $checklists)
    {
        return [
            'id' => (int) $checklists->id,
            'object_domain' => $checklists->object_domain, 
            'object_id' => $checklists->object_id,
            'description' => $checklists->description,
            'is_completed' => $checklists->is_completed,
            'due' => $this->formatDate($checklists->due),
            'urgency' => $checklists->urgency,
            'completed_at' => $checklists->completed_at,
            'last_update_by' => $checklists->updated_by,
            'updated_at' => $this->formatDate($checklists->updated_at),
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
}