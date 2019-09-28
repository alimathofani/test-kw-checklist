<?php

namespace App\Transformers;

use League\Fractal;
use App\Template;

class TemplateTransformer extends Fractal\TransformerAbstract
{
    public $type = 'templates';

    protected $availableIncludes = ['items','checklist'];
    
    public function __construct($tmp = null)
    {
        $this->tmp = $tmp;
    }
    
    public function transform(Template $templates)
    {
        switch ($this->tmp) {
            case 'all':
                return $this->allTemplate($templates);
                break;

            case 'show':
                return $this->showTemplate($templates);
                break;
            
            case 'create':
                $this->type = 'data';
                return $this->createTemplate($templates);
                break;
            
            case 'update':
                return $this->updateTemplate($templates);
                break;

            default:
                return $this->defaultTemplate($templates);
                break;
        }
    }

    public function allTemplate($templates)
    {
        return [
            'id' => $templates->id,
            'name' => $templates->name,
            'checklist' => [
                'due_unit' => $templates->checklist->due_unit,
                'description' => $templates->checklist->description,
                'due_interval' => $templates->checklist->due_interval,
            ],
            'items' => 
                collect($templates->checklist->templateItems)->map(function ($item, $key) {
                    return $item->only(['urgency', 'due_unit', 'description', 'due_interval']);
                })
        ];
    }

    public function showTemplate($templates)
    {
        return [
            'id' => $templates->id,
            'name' => $templates->name,
            'checklist' => [
                'due_unit' => $templates->checklist->due_unit,
                'description' => $templates->checklist->description,
                'due_interval' => $templates->checklist->due_interval,
            ],
            'items' => 
                collect($templates->checklist->templateItems)->map(function ($item, $key) {
                    return $item->only(['urgency', 'due_unit', 'description', 'due_interval']);
                })
        ];
    }

    public function createTemplate($templates)
    {
        return [
            'id' => $templates->id,
            'attributes' => [
                'name' => $templates->name,
                'checklist' => [
                    'due_unit' => $templates->checklist->due_unit,
                    'description' => $templates->checklist->description,
                    'due_interval' => $templates->checklist->due_interval,
                ],
                'items' => 
                collect($templates->checklist->templateItems)->map(function ($item, $key) {
                    return $item->only(['urgency', 'due_unit', 'description', 'due_interval']);
                })
            ],
        ];
    }

    public function updateTemplate($templates)
    {
        return [
            'id' => $templates->id,
            'attributes' => [
                'name' => $templates->name,
                'checklist' => [
                    'description' => $templates->checklist->description,
                    'due_interval' => $templates->checklist->due_interval,
                    'due_unit' => $templates->checklist->due_unit,
                ],
                'items' => 
                collect($templates->checklist->templateItems)->map(function ($item, $key) {
                    return $item->only(['urgency', 'due_unit', 'description', 'due_interval']);
                })
            ],
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
        return $this->collection($checklists->items, new GetItemTransformer(), 'items');
    }

    public function includeChecklist($checklists)
    {
        return $this->collection($checklists, new GetItemTransformer(), 'checklist');
    }
}