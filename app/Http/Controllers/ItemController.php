<?php

namespace App\Http\Controllers;

use App\Item;
use App\Checklist;
use Auth;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Transformers\CompleteItemTransformer;
use App\Transformers\SummariesItemTransformer;
use App\Transformers\BulkUpdateTransformer;
use App\Transformers\Serializer\CompleteItemSerializer;
use App\Transformers\Serializer\BulkUpdateItemSerializer;
use App\Transformers\Serializer\SummariesItemSerializer;
use App\Transformers\ItemTransformer;
use App\Transformers\AllItemTransformer;

class ItemController extends Controller
{
    public function getAllItems(Request $request)
    {
        $item = Item::paginate(10);
        if ($request->has('sort')) {
            if (preg_match("/^-/",$request->input('sort'))) {
                $item = Item::orderBy(ltrim($request->input('sort'), '-'), 'DESC');
            }else{
                $item = Item::orderBy($request->input('sort'), 'ASC');
            }
        }

        if ($request->has('sort')) {
            if (preg_match("/^-/",$request->input('sort'))) {
                $item = Item::orderBy(ltrim($request->input('sort'), '-'), 'DESC');
            }else{
                $item = Item::orderBy($request->input('sort'), 'ASC');
            }
        }

        return $this->respond('done', $this->paginate($item, new AllItemTransformer()));
    }

    public function getAllItemChecked($checklistId)
    {
        $checklist = Checklist::with(['items'])->find($checklistId);

        if(is_null($checklist)){
			return $this->respond('not_found');
        }
        
        return $this->respond('done', $this->item($checklist, new ItemTransformer('allChecked')));
    }

    public function getItem($checklistId, $itemId)
    {
        $checklist = Checklist::with(['items' => function ($q) use ($itemId) {
            $q->where('id',$itemId)->first();
        }])->find($checklistId);

        if(is_null($checklist)){
			return $this->respond('not_found');
        }
        
        $item = $checklist->items->first();
        
        if(is_null($item)){
            return $this->respond('not_found');
        }
        
        return $this->respond('done', $this->item($checklist, new ItemTransformer('show')));
    }

    public function createItem(Request $request, $checklistId)
    {
        $attributes = $request->data['attribute'];
        $checklist = Checklist::find($checklistId); 
        $item = $checklist->items()->create([
            'description' => $attributes['description'],
            'due' => $attributes['due'],
            'urgency' => $attributes['urgency'],
            'assignee_id' => $attributes['assignee_id'],
            'created_by' => Auth::user()->id,
        ]);
        
        return $this->respond('created', $this->item($checklist , new ItemTransformer('create')));
    }

    public function updateItem(Request $request, $checklistId, $itemId)
    {
        $attributes = $request->data['attribute'];
        
        $checklist = Checklist::with(['items' => function ($q) use ($itemId) {
            $q->where('id',$itemId)->first();
        }])->find($checklistId);
        
        if(is_null($checklist)){
            return $this->respond('not_found');
        }

        $item = $checklist->items->first();
        
        if(is_null($item)){
			return $this->respond('not_found');
        }

        $item->description = $attributes['description'];
        $item->due = $attributes['due'];
        $item->urgency = $attributes['urgency'];
        $item->assignee_id = $attributes['assignee_id'];
        $item->updated_by = Auth::user()->id;
        $item->save();
        
        return $this->respond('done', $this->item($checklist , new ItemTransformer('update')));
    }

    public function destroyItem($checklistId, $itemId)
    {
        $checklist = Checklist::with(['items' => function ($q) use ($itemId) {
            $q->where('id',$itemId)->first();
        }])->find($checklistId);
        
        if(is_null($checklist)){
			return $this->respond('not_found');
        }

        $item = $checklist->items->first();
        
        if(is_null($item)){
			return $this->respond('not_found');
        }
        
        $item->delete();

        return $this->respond('removed');
    }

    public function itemComplete(Request $request)
    {
        $items = $request->data;
        $array = array_map(function($value){
            return $value["item_id"];
        },$items);

        $items = Item::whereIn('id', $array)->update([
            'is_completed' => true, 
            'updated_by' => Auth::user()->id,
            'update_at' => date('Y-m-d H:i:s'),
        ]);
        
        $items = Item::whereIn('id', $array)->get();
        
        return $this->respond('done', $this->collection($items , new CompleteItemTransformer(), new CompleteItemSerializer));
    }

    public function itemIncomplete(Request $request)
    {
        $items = $request->data;
        $array = array_map(function($value){
            return $value["item_id"];
        },$items);

        $items = Item::whereIn('id', $array)->update([
            'is_completed' => false, 
            'updated_by' => Auth::user()->id,
            'update_at' => date('Y-m-d H:i:s'),
        ]);
        
        $items = Item::whereIn('id', $array)->get();
        
        return $this->respond('done', $this->collection($items , new CompleteItemTransformer(), new CompleteItemSerializer));
    }

    public function getSummaries(Request $request)
    {
        $date = date('Y-m-d');
        if ($request->has('tz')) {
            $tz = $request->get('tz');
            date_default_timezone_set($tz);
        }

        if ($request->has('date')) {
            $date = date('Y-m-d', strtotime($request->get('date')));
            // date('Y-m-d\TH:i:s.vP')
        }
        
        $this_week_start = date('Y-m-d', strtotime('-1 week monday 00:00:00'));
        $this_week_end = date('Y-m-d', strtotime('sunday 23:59:59'));
        $last_week_start = date('Y-m-d', strtotime('-2 week monday 00:00:00'));
        $last_week_end = date('Y-m-d', strtotime('-1 week sunday 23:59:59'));
        $month = date('m');
        
        $today = Checklist::sumToday($date);
        $past_due = Checklist::sumPastDue($date);
        $this_week = Checklist::sumThisWeek($this_week_start, $this_week_end);
        $past_week = Checklist::sumPastWeek($last_week_start, $last_week_end);
        $this_month = Checklist::sumThisMonth($month);
        $pass_month = Checklist::sumPastMonth($month);
        

        if ($request->has('object_domain')) {
            $object_domain = $request->get('object_domain');
            $today->where('object_domain', $object_domain);
            $past_due->where('object_domain', $object_domain);
            $this_week->where('object_domain', $object_domain);
            $past_week->where('object_domain', $object_domain);
            $this_month->where('object_domain', $object_domain);
            $pass_month->where('object_domain', $object_domain);
        }

        $today = $today->get()->map(function ($item, $key) {
            return $item->today_count;
        });
        $past_due = $past_due->get()->map(function ($item, $key) {
            return $item->past_due_count;
        });
        $this_week = $this_week->get()->map(function ($item, $key) {
            return $item->this_week_count;
        });
        $past_week = $past_week->get()->map(function ($item, $key) {
            return $item->past_week_count;
        });
        $this_month = $this_month->get()->map(function ($item, $key) {
            return $item->this_month_count;
        });
        $pass_month = $pass_month->get()->map(function ($item, $key) {
            return $item->pass_month_count;
        });

        $response = [
            'today' => $today->sum(),
            'past_due' => $past_due->sum(),
            'this_week' => $this_week->sum(),
            'past_week' => $past_week->sum(),
            'this_month' => $this_month->sum(),
            'past_month' => $pass_month->sum(),
            'total' => $this_month->sum()
        ];
        
        return $this->respond('done', $this->item($response, new SummariesItemTransformer(), new SummariesItemSerializer()));
    }

    public function bulkUpdate(Request $request, $checklistId)
    {
        $data = $request->data;
        $collection = collect();
        $checklist = Checklist::with(['items'])->find($checklistId);

        foreach ($data as $item) {
            switch ($item['action']) {
                case 'update':
                    $att = $item['attributes'];
                    $itemInstance = $checklist->items()->find($item['id']);
                    
                    if (is_null($itemInstance)) {
                        $response = [
                            'id' => $item['id'],
                            'action' => $item['action'],
                            'status' => 404
                        ];
                    }else{
                        $itemInstance->description = $att['description'];
                        $itemInstance->due = $att['due'];
                        $itemInstance->urgency = $att['urgency'];
                        $itemInstance->updated_by = Auth::user()->id;
                        $itemInstance->update_at = date('Y-m-d H:i:s');

                        if($itemInstance->save()){
                            $response = [
                                'id' => $itemInstance->id,
                                'action' => $item['action'],
                                'status' => 200
                            ];
                        }else{
                            $response = [
                                'id' => $itemInstance->id,
                                'action' => $item['action'],
                                'status' => 403
                            ];
                        }

                    }
                    
                    $collection->push($response);

                    break;

                default:
                    break;
            }
        }

        $checklist = $collection->all();

        return $this->respond('done', $this->collection($checklist, new BulkUpdateTransformer(), new BulkUpdateItemSerializer));
    }
}
