<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ChecklistTest extends TestCase
{
    /**
     * /checklist
     *
     * @return void
     */
    public function testShouldReturnAllChecklists()
    {
        $this->get("checklists", $this->header);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                [
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
                        'updated_at',
                        'created_at',
                    ],
                    'links' => [
                        'self'
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
     * /checklist/2
     *
     * @return void
     */
    public function testShouldReturnChecklists()
    {
        $this->get("checklists/2", $this->header);
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
                    'updated_at',
                    'created_at',
                ],
                'links' => [
                    'self'
                ]
            ]
        ]);
    }

    /**
     * /checklist/2
     *
     * @return void
     */
    public function testShouldCreateChecklists()
    {
        $parameters = [
            'data' => [
                'attributes' => [
                'object_domain' => "contact",
                'object_id' => "1",
                'due' => "2019-01-25T07:50:14+00:00",
                'urgency' => 1,
                'description' => "Need to verify this guy house.",
                'items' => [
                    "Visit his house",
                    "Capture a photo",
                    "Meet him on the house"
                ],
                'task_id' => "123"
                ]
            ]          
        ];
        
        $this->post("checklists", $parameters, $this->header);
        $this->seeStatusCode(201);
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
                    'task_id',
                    'urgency',
                    'completed_at',
                    'last_update_by',
                    'updated_at',
                    'created_at',
                ],
                'links' => [
                    'self'
                ]
            ]
        ]);

        
    }

    /**
     * /checklist/2
     *
     * @return void
     */
    public function testShouldUpdateChecklists()
    {
        $parameters = [
            'data' => [
                'type' => "checklists",
                'id' => 1,
                'attributes' => [
                  'object_domain' => "asperiores",
                  'object_id' => "1",
                  'description' => "Et iusto sunt autem ut. Facere aut ut ab omnis deserunt dolores. Veritatis ducimus id quia ut quo blanditiis. Autem quia reprehenderit cumque qui.",
                  'is_completed' => 1,
                  'completed_at' => null,
                  'created_at' => "2018-01-25T07:50:14+00:00"
                ],
                'links' => [
                  'self' => "https://dev-kong.command-api.kw.com/checklists/50127"
                ]
            ]          
        ];
        
        $this->patch("checklists/2", $parameters, $this->header);
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
                    'updated_at',
                    'created_at',
                ],
                'links' => [
                    'self'
                ]
            ]
        ]);
    }
    
    /**
     * /checklist/2
     *
     * @return void
     */
    public function testShouldDeleteChecklists()
    {
        $this->delete("checklists/5", [], $this->header);
        $this->seeStatusCode(204);
    }

    /**
     * /checklist/2
     *
     * @return void
     */
    public function testShouldAllChecklistUnauthorized()
    {
        $this->get("checklists", [], []);
        $this->seeStatusCode(401);
    }

    /**
     * /checklist/2
     *
     * @return void
     */
    public function testShouldChecklistUnauthorized()
    {
        $this->get("checklists/2", [], []);
        $this->seeStatusCode(401);
    }

    /**
     * /checklist/2
     *
     * @return void
     */
    public function testShouldCreateChecklistUnauthorized()
    {
        $parameters = [
            'data' => [
                'attributes' => [
                'object_domain' => "contact",
                'object_id' => "1",
                'due' => "2019-01-25T07:50:14+00:00",
                'urgency' => 1,
                'description' => "Need to verify this guy house.",
                'items' => [
                    "Visit his house",
                    "Capture a photo",
                    "Meet him on the house"
                ],
                'task_id' => "123"
                ]
            ]          
        ];
        
        $this->post("checklists", $parameters, []);
        $this->seeStatusCode(401);
        $this->seeJsonStructure([
            'status',
            'error'
        ]);
    }

    /**
     * /checklist/2
     *
     * @return void
     */
    public function testShouldUpdateChecklistUnauthorized()
    {
        $parameters = [
            'data' => [
                'type' => "checklists",
                'id' => 1,
                'attributes' => [
                  'object_domain' => "asperiores",
                  'object_id' => "1",
                  'description' => "Et iusto sunt autem ut. Facere aut ut ab omnis deserunt dolores. Veritatis ducimus id quia ut quo blanditiis. Autem quia reprehenderit cumque qui.",
                  'is_completed' => 1,
                  'completed_at' => null,
                  'created_at' => "2018-01-25T07:50:14+00:00"
                ],
                'links' => [
                  'self' => "https://dev-kong.command-api.kw.com/checklists/50127"
                ]
            ]          
        ];
        
        $this->patch("checklists/2", $parameters, []);
        $this->seeStatusCode(401);
        $this->seeJsonStructure([
            'status',
            'error'
        ]);
    }

    /**
     * /checklist/2
     *
     * @return void
     */
    public function testShouldUpdateChecklistNotfound()
    {
        $parameters = [
            'data' => [
                'type' => "checklists",
                'id' => 1,
                'attributes' => [
                  'object_domain' => "asperiores",
                  'object_id' => "1",
                  'description' => "Et iusto sunt autem ut. Facere aut ut ab omnis deserunt dolores. Veritatis ducimus id quia ut quo blanditiis. Autem quia reprehenderit cumque qui.",
                  'is_completed' => 1,
                  'completed_at' => null,
                  'created_at' => "2018-01-25T07:50:14+00:00"
                ],
                'links' => [
                  'self' => "https://dev-kong.command-api.kw.com/checklists/50127"
                ]
            ]          
        ];
        
        $this->patch("checklists/9999999999", $parameters, $this->header);
        $this->seeStatusCode(404);
        $this->seeJsonStructure([
            'status',
            'error'
        ]);
    }

    /**
     * /checklist/2
     *
     * @return void
     */
    public function testShouldDeleteChecklistNotfound()
    {
        $this->delete("checklists/9879879", [], $this->header);
        $this->seeStatusCode(404);
        $this->seeJsonStructure([
            'status',
            'error'
        ]);
    }

    /**
     * /checklist/2
     *
     * @return void
     */
    public function testShouldDeleteChecklistUnauthorized()
    {
        $this->delete("checklists/3", [], []);
        $this->seeStatusCode(401);
        $this->seeJsonStructure([
            'status',
            'error'
        ]);
    }
}
