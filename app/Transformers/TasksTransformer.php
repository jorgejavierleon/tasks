<?php


namespace App\Transformers;


use App\Task;
use League\Fractal\TransformerAbstract;

class TasksTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'users'
    ];

    /**
     * @param Task $task
     * @return array
     */
    public function transform(Task $task)
    {
        return [
            'id'   => (int) $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'due_date' => $task->due_date->toDateTimeString(),
            'priority_id' => (int) $task->priority_id
        ];
    }

    /**
     * @param Task $task
     * @return \League\Fractal\Resource\Collection
     */
    public function includeUsers(Task $task)
    {
        return $this->collection($task->users, new UsersTransformer());
    }
}