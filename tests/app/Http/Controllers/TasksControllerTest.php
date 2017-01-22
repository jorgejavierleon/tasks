<?php


class TasksControllerTest extends ControllerTestCase
{
    /**
     * @return void
     * @test
     */
    public function index_should_return_a_collection()
    {
        $tasks = factory(App\Task::class, 5)->create();

        $this->get('tasks')->seeStatusCode(200);

        $expected = [
            'data' => $tasks->toArray()
        ];

        $this->seeJsonEquals($expected);
    }

    /**
     * @return void
     * @test
     */
    public function it_shows_a_task()
    {
        $this->get('tasks/355')->seeStatusCode(404);

        $task = factory(App\Task::class)->create();
        $this->get('tasks/' . $task->id)->seeStatusCode(200);

        $expected = [
            'data' => $task->toArray()
        ];

        $this->seeJsonEquals($expected);
    }

    /**
     * @return void
     * @test
     */
    public function it_shows_optionally_includes_users()
    {
        $task = factory(App\Task::class)->create();
        $users = factory(App\User::class, 3)->create();
        foreach ($users as $user) {
            $task->users()->attach($user->id);
        }
        $this->assertCount(3, $task->users);

        $this->get("tasks/{$task->id}?include=users")
            ->seeStatusCode(200);

        $body = json_decode($this->response->getContent(), true);

        $this->assertArrayHasKey('data', $body);
        $data = $body['data'];
        $this->assertArrayHasKey('users', $data);
        $this->assertArrayHasKey('data', $data['users']);
        $this->assertCount(3, $data['users']['data']);

        $actual = $data['users']['data'][0];
        $user = $users->first();
        $this->assertEquals($user->id, $actual['id']);
        $this->assertEquals($user->firstname, $actual['firstname']);
        $this->assertEquals($user->lastname, $actual['lastname']);
        $this->assertEquals($user->email, $actual['email']);
    }

    /**
     * @return void
     * @test
     */
    public function it_stores_a_new_task()
    {
        $task = factory(App\Task::class)->make();
        $postData = [
            'title' => $task->title,
            'description' => $task->description,
            'due_date' => $task->due_date->toDateTimeString(),
        ];

        $this->post('tasks', $postData, ['Accept' => 'application/json']);

        $this->seeStatusCode(201);
        $data = $this->response->getData(true);
        $this->assertArrayHasKey('data', $data);
        $this->seeJson($postData);

        $this->seeInDatabase('tasks', ['title' => $task->title]);
    }

    /**
     * @return void
     * @test
     */
    public function it_validates_fields()
    {
        $this->post('tasks', [], ['Accept' => 'application/json']);

        $data = $this->response->getData(true);
        $fields = ['title', 'description', 'due_date'];

        foreach ($fields as $field) {
            $this->assertArrayHasKey($field, $data);
            $noSnakeField = str_replace("_"," ",$field);
            $this->assertEquals(["The {$noSnakeField} field is required."], $data[$field]);
        }
    }

    /**
     * @return void
     * @test
     */
    public function it_updates_a_task()
    {
        $task = factory(App\Task::class)->create();
        $putData = [
            'title' => 'New title',
            'description' => $task->description,
            'due_date' => $task->due_date->toDateTimeString(),
        ];

        $this->put(
            'tasks/' . $task->id,
            $putData,
            ['Accept' => 'application/json']
        );

        $this->seeStatusCode(200)
            ->seeJson($putData);

        $this->seeInDatabase('tasks', ['title' => 'New title']);
        $this->notSeeInDatabase('tasks', ['title' => $task->title]);

        $this->assertArrayHasKey('data', $this->response->getData(true));
    }

    /**
     * @return void
     * @test
     */
    public function it_deletes_a_task()
    {
        $task = factory(App\Task::class)->create();

        $this->delete('tasks/' . $task->id);
        $this->seeStatusCode(204)->isEmpty();
        $this->notSeeInDatabase('tasks', ['id' => $task->id]);
    }
}