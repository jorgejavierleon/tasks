<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class UsersControllerTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * @return void
     * @test
     */
    public function index_status_should_be_200()
    {
        $this->get('/users')->seeStatusCode(200);
    }

    /**
     * @return void
     * @test
     */
    public function index_should_return_a_collection()
    {
        $this->get('/users')
            ->seeJson([
                'firstname' => 'Pedaro'
            ])->seeJson([
                'firstname' => 'María'
            ]);
    }
}