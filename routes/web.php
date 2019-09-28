<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/register', 'AuthController@register');
$router->post('/login', 'AuthController@login');

$router->group([
    'middleware' => 'auth'
], function () use ($router) {
    
    $router->get('/checklists/items', 'ItemController@getAllItems'); // done + test
    $router->get('/checklists/items/summaries', 'ItemController@getSummaries'); // done + test

    $router->get('/checklists/templates', 'TemplateController@index'); // done + test
    $router->post('/checklists/templates', 'TemplateController@create'); // done + test
    $router->get('/checklists/templates/{templateId}', 'TemplateController@show'); // done + test
    $router->patch('/checklists/templates/{templateId}', 'TemplateController@update'); // done + test
    $router->delete('/checklists/templates/{templateId}', 'TemplateController@destroy'); // done + test
    $router->post('/checklists/templates/{templateId}/assigns', 'TemplateController@assign'); // done + test

    $router->get('/checklists', 'ChecklistController@index'); // done + test
    $router->post('/checklists', 'ChecklistController@create'); // done + test
    $router->get('/checklists/{checklistId}', 'ChecklistController@show'); // done + test
    $router->patch('/checklists/{checklistId}', 'ChecklistController@update'); // done + test
    $router->delete('/checklists/{checklistId}', 'ChecklistController@destroy'); // done + test
    
    $router->get('/checklists/{checklistId}/items', 'ItemController@getAllItemChecked'); // done + test
    $router->post('/checklists/{checklistId}/items', 'ItemController@createItem'); // done + test
    $router->get('/checklists/{checklistId}/items/{itemId}', 'ItemController@getItem'); // done + test
    $router->patch('/checklists/{checklistId}/items/{itemId}', 'ItemController@updateItem'); // done + test
    $router->delete('/checklists/{checklistId}/items/{itemId}', 'ItemController@destroyItem'); // done + test
    
    $router->post('/checklists/complete', 'ItemController@itemComplete'); // done + test 
    $router->post('/checklists/incomplete', 'ItemController@itemIncomplete'); // done + test
    
    $router->post('/checklists/{checklistId}/items/_bulk', 'ItemController@bulkUpdate'); // done + test
    
});

