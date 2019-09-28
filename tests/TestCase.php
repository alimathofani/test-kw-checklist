<?php

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    protected $header;

    protected function setUp():void
    {
        parent::setUp();
        
        Artisan::call('migrate:refresh --seed');

        $user = factory(User::class)->create([
            'password' => Hash::make($password = 'secret'),
        ]);
        
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ])->response;
        
        $this->seeStatusCode(201);

        $response = json_decode($response->getContent());

        $this->header = [ 'Authorization' => 'Bearer '. $response->data->api_token ];
    }
}
