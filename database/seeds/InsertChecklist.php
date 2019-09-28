<?php

use Illuminate\Database\Seeder;
use App\Checklist;

class InsertChecklist extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $checklist = Checklist::create([
            'object_domain' => 'contact', 
            'object_id' => 1,
            'description' => 'Need to verify this guy house.',
            'is_completed' => false,
            'due' => null,
            'urgency' => 0,
            'completed_at' => null,
            'updated_by' => null
        ]);
    }
}
