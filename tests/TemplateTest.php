<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class TemplateTest extends TestCase
{
    public function testShouldReturnAllTemplate()
    {
        $this->get("checklists/templates", $this->header);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                [
                    'name',    
                    'checklist' => [
                        'description',
                        'due_interval',
                        'due_unit',
                    ],
                    'items' => [
                        [
                            'description',
                            'urgency',
                            'due_interval',
                            'due_unit',
                        ],
                        [
                            'description',
                            'urgency',
                            'due_interval',
                            'due_unit',
                        ]
                    ]
                ]
            ],
            'links' => [
                'first',
                'last',
                'next',
                'prev',
            ],
            'meta' => [
                'total',
                'count',
                'per_page',
                'current_page',
                'total_pages',
            ]
        ]);
    }

    /**
     * /checklist
     *
     * @return void
     */
    public function testShouldReturnGetTemplate()
    {
        $this->get("checklists/templates/1", $this->header);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes' =>
                [
                    'name',
                    'items' => [
                        [
                            'urgency' ,
                            'due_unit',
                            'description',
                            'due_interval',
                        ]
                    ],
                    'checklist' => [
                        'due_unit',
                        'description',
                        'due_interval',
                    ]
                ],
                'links' => [
                    'self'
                ]
            ]
            
        ]);
    }

    public function testShouldReturnCreateTemplate()
    {
        $parameters = [
            'data' => [
                'attributes' => [
                    'name' => "foo template",
                    'checklist' => [
                        'description' => "my checklist",
                        'due_interval' => 3,
                        'due_unit' => "hour"
                    ],
                    'items' => [
                        [
                            'description' => "my foo item",
                            'urgency' => 2,
                            'due_interval' => 40,
                            'due_unit' => "minute"
                        ],
                        [
                            'description' => "my bar item",
                            'urgency' => 3,
                            'due_interval' => 30,
                            'due_unit' => "minute"
                        ]
                    ]
                ]
            ]        
        ];

        $this->post("/checklists/templates", $parameters, $this->header);
        $this->seeStatusCode(201);
        $this->seeJsonStructure([
            'data' => [
                'attributes' => [
                    'name',
                    'checklist' => [
                        'description',
                        'due_interval',
                        'due_unit',
                    ],
                    'items' => [
                        [
                            'description',
                            'urgency',
                            'due_interval',
                            'due_unit',
                        ],
                        [
                            'description',
                            'urgency',
                            'due_interval',
                            'due_unit',
                        ]
                    ]
                ]
            ]
        ]);
    }

    public function testShouldReturnUpdateTemplate()
    {
        $parameters = [
            'data' => [
                'name' => "foo template",
                'checklist' => [
                    'description' => "my checklist",
                    'due_interval' => 3,
                    'due_unit' => "hour"
                ],
                'items' => [
                    [
                        'description' => "my foo item",
                        'urgency' => 2,
                        'due_interval' => 40,
                        'due_unit' => "minute"
                    ],
                    [
                        'description' => "my bar item",
                        'urgency' => 3,
                        'due_interval' => 30,
                        'due_unit' => "minute"
                    ]
                ]
            ]      
        ];

        $this->patch("/checklists/templates/3", $parameters, $this->header);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                'id',
                'attributes' => [
                    'name',
                    'checklist' => [
                        'description',
                        'due_interval',
                        'due_unit',
                    ],
                    'items' => [
                        [
                            'description',
                            'urgency',
                            'due_interval',
                            'due_unit',
                        ],
                        [
                            'description',
                            'urgency',
                            'due_interval',
                            'due_unit',
                        ]
                    ]
                ]
            ]
        ]);
    }

    public function testShouldDeleteTemplate()
    {
        $this->delete("/checklists/templates/1", [], $this->header);
        $this->seeStatusCode(204);
    }

    public function testShouldReturnAssignsTemplate()
    {
        $parameters = [
            'data' => [
                [
                  'attributes' => [
                        'object_id' => 1,
                        'object_domain' => "et"
                    ]
                ],
                [
                  'attributes' => [
                        'object_id' => 2,
                        'object_domain' => "et"
                    ]
                ],
                [
                  'attributes' => [
                        'object_id' => 3,
                        'object_domain' => "et"
                    ]
                ]
            ]    
        ];

        $this->post("/checklists/templates/2/assigns", $parameters, $this->header);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                [
                    'type',    
                    'id',   
                    'attributes'  => [
                        'object_domain',
                        'object_id',
                        'description',
                        'is_completed',
                        'due',
                        'urgency',
                        'completed_at',
                        'updated_by',
                        'created_by',
                        'updated_at',
                        'created_at',
                    ],
                    'links' => [
                        'self'
                    ],
                    'relationships' => [
                        'items' => [
                            'links' => [
                                'self',
                                'related',
                            ],
                            'data' => [
                                [
                                    'type',
                                    'id',
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'included' => [
                [
                  'type',
                  'id',
                  'attributes' => [
                    'description',
                    'is_completed',
                    'completed_at',
                    'due',
                    'urgency',
                    'updated_by',
                    'user_id',
                    'checklist_id',
                    'deleted_at',
                    'created_at',
                    'updated_at',
                  ],
                  'links' => [
                    'self'
                  ]
                ],
            ],
            'meta' => [
                'total',
                'count',
                'per_page',
                'current_page',
                'total_pages',
            ]
        ]);
    }
}
