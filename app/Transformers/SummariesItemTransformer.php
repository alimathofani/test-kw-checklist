<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class SummariesItemTransformer extends TransformerAbstract
{
    public $type = 'data';

    public function transform($summaries)
    {
        return [
            'today' =>  $summaries['today'],
            'past_due' =>  $summaries['past_due'],
            'this_week' =>  $summaries['this_week'],
            'past_week' =>  $summaries['past_week'],
            'this_month' =>  $summaries['this_month'],
            'past_month' =>  $summaries['past_month'],
            'total' =>  $summaries['total']
        ];
    }

}