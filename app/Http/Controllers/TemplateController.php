<?php

namespace App\Http\Controllers;

use App\Template;
use App\Checklist;
use App\Item;
use Illuminate\Http\Request;
use App\Transformers\TemplateTransformer;
use App\Transformers\BulkUpdateTransformer;
use App\Transformers\GetItemSerializer;
use App\Transformers\AssignTemplateTransformer;
use App\Transformers\Serializer\CreateTemplateSerializer;
use App\Transformers\Serializer\AllTemplateSerializer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class TemplateController extends Controller
{
    public function index()
    {
        return $this->respond('done', $this->paginate(Template::paginate(10), new TemplateTransformer('all'), new AllTemplateSerializer()));
    }

    public function show($templateId)
    {
        $template = Template::with(['checklist' => function($q){
            $q->with(['items']);
        }])->find($templateId);

        if(is_null($template)){
			return $this->respond('not_found');
        }
        
        return $this->respond('done', $this->item($template, new TemplateTransformer('show')));
    }

    public function create(Request $request)
    {
        $attributes = $request->data['attributes'];
        $unit = $attributes['checklist']['due_unit'];
        switch ($unit) {
            case 'hour':
                $unit = $attributes['checklist']['due_unit'];
                break;
            case 'minute':
                $unit = $attributes['checklist']['due_unit'];
                break;
            default:
            return $this->respond('not_valid');
                break;
        }

        $interval = $attributes['checklist']['due_interval'];
        
        $start = date('Y-m-d H:i:s');
        $due = date('Y-m-d H:i:s', strtotime('+'.$interval.' '.$unit , strtotime($start)));
        $template = Template::create(['name' => $attributes['name']]);
        $checklist = $template->checklist()->create([
            'description' => $attributes['checklist']['description'],
            'due' => $due,
            'object_domain' => '',
            'object_id' => 0
        ]);
        foreach ($attributes['items'] as $data) {
            $dueItem = date('Y-m-d H:i:s', strtotime('+'.$data['due_interval'].' '.$data['due_unit'] , strtotime($start)));
            $checklist->items()->create([
                'description' => $data['description'],
                'urgency' => $data['urgency'],
                'due' => $dueItem,
            ]);
        }

        return $this->respond('created', $this->item($template, new TemplateTransformer('create'), new CreateTemplateSerializer()));
    }
    
    public function update(Request $request, $templateId)
    {
        $attributes = $request->data;
        $unit = $attributes['checklist']['due_unit'];
        switch ($unit) {
            case 'hour':
                $unit = $attributes['checklist']['due_unit'];
                break;
            case 'minute':
                $unit = $attributes['checklist']['due_unit'];
                break;
            default:
            return $this->respond('not_valid');
                break;
        }
        
        $template = Template::with(['checklist'])->find($templateId);
        
        if(is_null($template)){
			return $this->respond('not_found');
        }

        $interval = $attributes['checklist']['due_interval'];
        $start = date('Y-m-d H:i:s');

        $due = date('Y-m-d H:i:s', strtotime('+'.$interval.' '.$unit , strtotime($start)));        
        $checklistIntance = $template->checklist()->update([
            'description' => $attributes['checklist']['description'],
            'due' => $due
        ]);

        $itemIntance = $template->checklist->items;
        $start = date('Y-m-d H:i:s');
        $items = $attributes['items'];
        foreach ($items as $key => $item) {
            $dueItem = date('Y-m-d H:i:s', strtotime('+'.$item['due_interval'].' '.$item['due_unit'] , strtotime($start)));         
            
            $itemIntance[$key]->update([
                'description' => $item['description'],
                'urgency' => $item['urgency'],
                'due' => $dueItem
            ]);
        }
        

        return $this->respond('done', $this->item($template, new TemplateTransformer('update'), new CreateTemplateSerializer()));
    }

    public function destroy($templateId)
    {
        $template = Template::find($templateId);

        if(is_null($template)){
			return $this->respond('not_found');
        }

        $items = $template->checklist->items()->first();
        if ($items) {
            $template->checklist->items()->delete();
        }

        $template->checklist()->delete();
        
        $template->delete();

        return $this->respond('removed');
    }

    public function assign(Request $request, $templateId)
    {
        $data = $request->data;
        $template = Template::find($templateId);
        $collect = new Collection();
        foreach ($data as $check) {
            $object_id = $check['attributes']['object_id'];
            $object_domain = $check['attributes']['object_domain'];
            
            $checklists = Checklist::where([
                'object_id' => $object_id,
                'object_domain' => $object_domain
            ])->with(['items'])->get();
            
            foreach ($checklists as $checklist) {
                $checklist->assign_id = $template->id;
                $checklist->updated_by = Auth::user()->id;
                $checklist->save();
                $collect = $collect->push($checklist);
            }
        }
        
        return $this->respond('done', $this->paginate($this->paginator($collect), new AssignTemplateTransformer()));

    }

    public function paginator($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

}
