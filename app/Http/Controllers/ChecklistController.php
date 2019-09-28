<?php

namespace App\Http\Controllers;

use App\Checklist;
use App\Item;
use Illuminate\Http\Request;
use App\Transformers\ChecklistTransformer;
use App\Transformers\BulkUpdateTransformer;
use App\Transformers\GetItemSerializer;
use Auth;

class ChecklistController extends Controller
{
    public function index()
    {
        return $this->respond('done', $this->paginate(Checklist::paginate(10), new ChecklistTransformer('all')));
    }

    public function show($checklistId)
    {
        $checklist = Checklist::find($checklistId);

        if(is_null($checklist)){
			return $this->respond('not_found');
        }
        
        return $this->respond('done', $this->item($checklist, new ChecklistTransformer('show')));
    }

    public function create(Request $request)
    {
        $attributes = $request->data['attributes'];
        $checklist = Checklist::create([
            'object_domain' => $attributes['object_domain'],
            'object_id' => $attributes['object_id'],
            'due' => date('Y-m-d H:i:s', strtotime($attributes['due'])),
            'urgency' => $attributes['urgency'],
            'description' => $attributes['description'],
            'assign_id' => $attributes['task_id'],
            'created_by' => Auth::user()->id,
        ]);
        foreach ($attributes['items'] as $data) {
            $checklist->items()->create([
                'description' => $data,
                'task_id' => $attributes['task_id'],
            ]);
        }

        return $this->respond('created', $this->item($checklist, new ChecklistTransformer('create')));
    }
    
    public function update(Request $request, $checklistId)
    {
        $attributes = $request->data['attributes'];

        $checklist = Checklist::find($checklistId);
        
        if(is_null($checklist)){
			return $this->respond('not_found');
        }
        
        $checklist->object_domain = $attributes['object_domain'];
        $checklist->object_id = $attributes['object_id'];
        $checklist->description = $attributes['description'];
        $checklist->is_completed = $attributes['is_completed'];
        $checklist->completed_at = $attributes['completed_at'];
        $checklist->updated_by = Auth::user()->id;
        $checklist->save();

        return $this->respond('done', $this->item($checklist, new ChecklistTransformer('update')));
    }

    public function destroy($checklistId)
    {
        $checklist = Checklist::find($checklistId);

        if(is_null($checklist)){
			return $this->respond('not_found');
        }
        
        $checklist->delete();

        return $this->respond('removed');
    }

}
