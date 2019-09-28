<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ItemTest extends TestCase
{
    /**
     * /checklist
     *
     * @return void
     */
    public function testShouldReturnAllItemsCheck()
    {
        $this->get("checklists/1/items", $this->header);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes' =>
                [
                    'object_domain',
                    'object_id',
                    'description',
                    'is_completed',
                    'due',
                    'urgency',
                    'completed_at',
                    'last_update_by',
                    'update_at',
                    'created_at',
                    'items' => [
                        [
                            'id',
                            'name',
                            'user_id',
                            'is_completed',
                            'due',
                            'urgency',
                            'checklist_id',
                            'assignee_id',
                            'task_id',
                            'completed_at',
                            'last_update_by',
                            'update_at',
                            'created_at',
                        ]
                    ]
                ],
                'links' => [
                    'self'
                ]
            ]
            
        ]);
    }

    public function testShouldReturnCreateItemsCheck()
    {
        $parameters = [
            'data' => [
                'attribute' => [
                  'description' => "Need to verify this guy house.",
                  'due' => "2019-01-19 18:34:51",
                  'urgency' => "2",
                  'assignee_id' => 123
                ]
            ]        
        ];

        $this->post("checklists/2/items", $parameters, $this->header);
        $this->seeStatusCode(201);
        $this->seeJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes' =>
                [
                    'description',
                    'is_completed',
                    'completed_at',
                    'due',
                    'urgency',
                    'updated_by',
                    'updated_at',
                    'created_at',
                ],
                'links' => [
                    'self'
                ]
            ]
        ]);
    }

    public function testShouldReturnGetItemsCheck()
    {
        $this->get("checklists/1/items/1", $this->header);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes' =>
                [
                    'description',
                    'is_completed',
                    'completed_at',
                    'due',
                    'urgency',
                    'update_by',
                    'created_by',
                    'checklist_id',
                    'assignee_id',
                    'task_id',
                    'deleted_at',
                    'updated_at',
                    'created_at',
                ],
                'links' => [
                    'self'
                ]
            ]
            
        ]);
    }

    public function testShouldReturnUpdateItemsCheck()
    {
        $parameters = [
            'data' => [
                'attribute' => [
                  'description' => "Need to verify this guy house.",
                  'due' => "2019-01-19 18:34:51",
                  'urgency' => "2",
                  'assignee_id' => 123
                ]
            ]        
        ];

        $this->patch("checklists/1/items/2", $parameters, $this->header);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes' =>
                [
                    'description',
                    'is_completed',
                    'due',
                    'urgency',
                    'assignee_id',
                    'completed_at',
                    'updated_by',
                    'updated_at',
                    'created_at',
                ],
                'links' => [
                    'self'
                ]
            ]
            
        ]);
    }

    public function testShouldDeleteItemCheck()
    {
        $this->delete("checklists/1/items/1", [], $this->header);
        $this->seeStatusCode(204);
    }

    public function testShouldReturnCompleteItemsCheck()
    {
        $parameters = [
            'data' => [
                [
                    'item_id' => 1
                ],
                [
                    'item_id' => 2
                ],
                [
                    'item_id' => 3
                ],
                [
                    'item_id' => 4
                ]
            ]        
        ];

        $this->post("checklists/complete", $parameters, $this->header);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                [
                    'id',
                    'item_id',
                    'is_completed',
                    'checklist_id',
                ]
            ]
            
        ]);
    }

    public function testShouldReturnIncompleteItemsCheck()
    {
        $parameters = [
            'data' => [
                [
                    'item_id' => 1
                ],
                [
                    'item_id' => 2
                ],
                [
                    'item_id' => 3
                ],
                [
                    'item_id' => 4
                ]
            ]        
        ];

        $this->post("checklists/incomplete", $parameters, $this->header);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                [
                    'id',
                    'item_id',
                    'is_completed',
                    'checklist_id',
                ]
            ]
            
        ]);
    }

    public function testShouldReturnBulkUpdateItemsCheck()
    {
        $parameters = [
            'data' => [
                [
                    'id' => "64",
                    'action' => "update",
                    'attributes' => [
                        'description' => "",
                        'due' => "2019-01-19 18:34:51",
                        'urgency' => "2"
                    ]
                ],
                [
                    'id' => "205",
                    'action' => "update",
                    'attributes' => [
                        'description' => "{{data.attributes.description}}",
                        'due' => "2019-01-19 18:34:51",
                        'urgency' => "2"
                    ]
                ]
            ]        
        ];

        $this->post("/checklists/2/items/_bulk", $parameters, $this->header);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                [
                    'id',
                    'action',
                    'status',
                ]
            ]
        ]);
    }

    public function testShouldReturnSummariesItemsCheck()
    {
        $this->get("/checklists/items/summaries", $this->header);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                'today',
                'past_due',
                'this_week',
                'past_week',
                'this_month',
                'past_month',
                'total',
            ]
        ]);
    }

    public function testShouldReturnAllItems()
    {
        $this->get("checklists/items", $this->header);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                [
                    'type',
                    'id',
                    'attributes' =>
                    [
                        'description',
                        'is_completed',
                        'completed_at',
                        'completed_by',
                        'due',
                        'urgency',
                        'updated_by',
                        'created_by',
                        'checklist_id',
                        'assignee_id',
                        'task_id',
                        'deleted_at',
                        'created_at',
                        'updated_at',
                    ],
                    'links' => [
                        'self'
                    ]
                ]
            ]
            
        ]);
    }
}
