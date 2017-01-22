<?php


namespace App\Transformers;


use App\Task;
use League\Fractal\TransformerAbstract;

class TasksTransformer extends TransformerAbstract
{
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
            'due_date' => $task->due_date,
        ];
    }
}