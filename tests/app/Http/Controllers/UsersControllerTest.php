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

        $this->get('users')->seeStatusCode(200);

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
        $this->get('users/' . $user->id)->seeStatusCode(200);

        $expected = [
            'data' => $user->toArray()
        ];

        $this->seeJsonEquals($expected);
    }

    /**
     * users/{$user->id}?include=tasks
     * @return void
     * @test
     */
    public function it_shows_optionally_includes_tasks()
    {
        $user = factory(App\User::class)->create();
        $tasks = factory(App\Task::class, 3)->create();
        foreach ($tasks as $task) {
            $user->tasks()->attach($task->id);
        }
        $this->assertCount(3, $user->tasks);

        $this->get("users/{$user->id}?include=tasks")
            ->seeStatusCode(200);

        $body = json_decode($this->response->getContent(), true);

        $this->assertArrayHasKey('data', $body);
        $data = $body['data'];
        $this->assertArrayHasKey('tasks', $data);
        $this->assertArrayHasKey('data', $data['tasks']);
        $this->assertCount(3, $data['tasks']['data']);

        $actual = $data['tasks']['data'][0];
        $task = $tasks->first();
        $this->assertEquals($task->id, $actual['id']);
        $this->assertEquals($task->title, $actual['title']);
        $this->assertEquals($task->description, $actual['description']);
        $this->assertEquals($task->due_date, $actual['due_date']);
    }

    /**
     * @return void
     * @test
     */
    public function it_shows_404_error_for_no_user_found()
    {
        $this->get('users/35')->seeStatusCode(404);

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
    public function it_stores_a_new_user()
    {
        $user = factory(App\User::class)->make();
        $postData = [
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
            'password' => 'secret',
        ];

        $this->post('users', $postData, ['Accept' => 'application/json']);

        $this->seeStatusCode(201);
        $data = $this->response->getData(true);
        $this->assertArrayHasKey('data', $data);
        $this->seeJson(array_except($postData, ['password']));

        $this->seeInDatabase('users', ['email' => $user->email]);
    }

    /**
     * @return void
     * @test
     */
    public function it_validates_fields()
    {
        $this->post('users', [], ['Accept' => 'application/json']);

        $data = $this->response->getData(true);
        $fields = ['firstname', 'lastname', 'email', 'password'];

        foreach ($fields as $field) {
            $this->assertArrayHasKey($field, $data);
            $this->assertEquals(["The {$field} field is required."], $data[$field]);
        }
    }

    /**
     * @return void
     * @test
     */
    public function it_updates_a_user()
    {
        $user = factory(App\User::class)->create();
        $putData = [
            'firstname' => 'New name',
            'lastname' => $user->lastname,
            'email' => $user->email,
        ];

        $this->put(
            'users/' . $user->id,
            $putData,
            ['Accept' => 'application/json']
        );

        $this->seeStatusCode(200)
            ->seeJson($putData);

        $this->seeInDatabase('users', ['firstname' => 'New name']);
        $this->notSeeInDatabase('users', ['firstname' => $user->firstname]);

        $this->assertArrayHasKey('data', $this->response->getData(true));
    }

    /**
     * @return void
     * @test
     */
    public function it_adds_a_task_to_a_user()
    {
        $user = factory(App\User::class)->create();
        $task = factory(App\Task::class)->create();

        $this->assertCount(0, $user->tasks);

        $this->put(
            "users/{$user->id}/tasks/{$task->id}",
            [], ['Accept' => 'application/json']
        );

        $this->seeStatusCode(200);

        $dbUser = \App\User::find($user->id);
        $this->assertCount(1, $dbUser->tasks);

        $body = $this->response->getData(true);
        $this->assertArrayHasKey('data', $body);

        // Ensure the task id is in the response.
        $this->assertArrayHasKey('tasks', $body['data']);
        $this->assertArrayHasKey('data', $body['data']['tasks']);

        // Make sure the task is in the response
        $tasks = $body['data']['tasks'];
        $this->assertEquals($task->id, $tasks['data'][0]['id']);
    }

    /**
     * @return void
     * @test
     */
    public function it_removes_a_task_from_a_user()
    {
        $user = factory(App\User::class)->create();
        $tasks = factory(App\Task::class, 3)->create();
        foreach ($tasks as $task) {
            $user->tasks()->attach($task->id);
        }
        $this->assertCount(3, $user->tasks);

        $task = $tasks->first();

        $this->delete("users/{$user->id}/tasks/{$task->id}");
        $this->seeStatusCode(204)->isEmpty();
        $this->notSeeInDatabase('task_user', [
            'user_id' => $user->id,
            'task_id' => $task->id
        ]);
    }

    /**
     * @return void
     * @test
     */
    public function it_deletes_a_user()
    {
        $user = factory(App\User::class)->create();

        $this->delete('users/' . $user->id);
        $this->seeStatusCode(204)->isEmpty();
        $this->notSeeInDatabase('users', ['id' => $user->id]);
    }
}