<?php


class UsersControllerTest extends ControllerTestCase
{
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
        $users = factory(App\User::class, 5)->create();

        $this->get('/users');
        $expected = [
            'data' => $users->toArray()
        ];

        $this->seeJsonEquals($expected);
    }
}