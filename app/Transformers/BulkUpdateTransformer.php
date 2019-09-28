<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class BulkUpdateTransformer extends TransformerAbstract
{
    public $type = 'data';

    public function transform($items)
    {
        return [
            'id' => (string) $items['id'],
            'action' => (string) $items['action'],
            'status' => (int) $items['status']
        ];
    }

}