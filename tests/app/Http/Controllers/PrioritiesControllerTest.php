<?php


class PrioritiesControllerTest extends ControllerTestCase
{
    /**
     * @return void
     * @test
     */
    public function index_should_return_a_collection()
    {
        $priorities = factory(App\Priority::class, 5)->create();

        $this->get('priorities')->seeStatusCode(200);

        $expected = [
            'data' => $priorities->toArray()
        ];

        $this->seeJsonEquals($expected);
    }

    /**
     * @return void
     * @test
     */
    public function it_shows_a_priority()
    {
        $this->get('priorities/355')->seeStatusCode(404);

        $priority = factory(App\Priority::class)->create();
        $this->get('priorities/' . $priority->id)->seeStatusCode(200);

        $expected = [
            'data' => $priority->toArray()
        ];

        $this->seeJsonEquals($expected);
    }

    /**
     * @return void
     * @test
     */
    public function it_shows_optionally_includes_tasks()
    {
        $priority = factory(App\Priority::class)->create();
        $tasks = factory(App\Task::class, 3)->create([
            'priority_id' => $priority->id
        ]);

        $this->get("priorities/{$priority->id}?include=tasks")
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
    }

    /**
     * @return void
     * @test
     */
    public function it_stores_a_new_priority()
    {
        $priority = factory(App\Priority::class)->make();
        $postData = ['name' => $priority->name,];

        $this->post('priorities', $postData, ['Accept' => 'application/json']);

        $this->seeStatusCode(201);
        $data = $this->response->getData(true);
        $this->assertArrayHasKey('data', $data);
        $this->seeJson($postData);

        $this->seeInDatabase('priorities', ['name' => $priority->name]);
    }

    /**
     * @return void
     * @test
     */
    public function it_validates_fields()
    {
        $this->post('priorities', [], ['Accept' => 'application/json']);

        $data = $this->response->getData(true);
        $fields = ['name'];

        foreach ($fields as $field) {
            $this->assertArrayHasKey($field, $data);
            $this->assertEquals(["The {$field} field is required."], $data[$field]);
        }
    }

    /**
     * @return void
     * @test
     */
    public function it_updates_a_priority()
    {
        $priority = factory(App\Priority::class)->create();
        $putData = [
            'name' => 'New name',
        ];

        $this->put(
            'priorities/' . $priority->id,
            $putData,
            ['Accept' => 'application/json']
        );

        $this->seeStatusCode(200)
            ->seeJson($putData);

        $this->seeInDatabase('priorities', ['name' => 'New name']);
        $this->notSeeInDatabase('priorities', ['name' => $priority->name]);

        $this->assertArrayHasKey('data', $this->response->getData(true));
    }

    /**
     * @return void
     * @test
     */
    public function it_deletes_a_priority()
    {
        $priority = factory(App\Priority::class)->create();

        $this->delete('priorities/' . $priority->id);
        $this->seeStatusCode(204)->isEmpty();
        $this->notSeeInDatabase('priorities', ['id' => $priority->id]);
    }
}