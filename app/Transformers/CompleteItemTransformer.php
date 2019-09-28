<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class CompleteItemTransformer extends TransformerAbstract
{
    public $type = 'data';

    public function transform($items)
    {
        return [
            'id' => $items->id,
            'item_id' => $items->id,
            'is_completed' => $items->is_completed,
            'checklist_id' => $items->checklist_id,
        ];
    }

}