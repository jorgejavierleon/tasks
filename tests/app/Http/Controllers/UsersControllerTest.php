<?php


class UsersControllerTest extends ControllerTestCase
{

    /**
     * @return void
     * @test
     */
    public function index_should_return_a_collection()
    {
        $users = factory(App\User::class, 5)->create();

        $this->get('/users')->seeStatusCode(200);

        $expected = [
            'data' => $users->toArray()
        ];

        $this->seeJsonEquals($expected);
    }

    /**
     * @return void
     * @test
     */
    public function it_shows_a_user()
    {
        $user = factory(App\User::class)->create();
        $this->get('/users/' . $user->id)->seeStatusCode(200);

        $expected = [
            'data' => $user->toArray()
        ];

        $this->seeJsonEquals($expected);
    }

    /**
     * @return void
     * @test
     */
    public function it_shows_404_error_for_no_user_found()
    {
        $this->get('/users/35')->seeStatusCode(404);

        $expected = [
            'error' => [
                'http_code' => 404,
                'message' => 'Resource not found',
            ]
        ];

        $this->seeJsonEquals($expected);
    }

    /**
     * @return void
     * @test
     */
//    public function it_stores_a_new_user()
//    {
//        $user = factory(App\User::class)->make();
//        $postData = [
//            'firstname' => $user->firstname,
//            'lastname' => $user->lastname,
//            'email' => $user->email,
//            'password' => 'secret',
//        ];
//
//        $this->post('/users', $postData, ['Accept' => 'application/json']);
//
//        $this->seeStatusCode(201);
//        $data = $this->response->getData(true);
//        $this->assertArrayHasKey('data', $data);
//        $this->seeJson($postData);
//
//        $this->seeInDatabase('users', $postData['email']);
//    }
}