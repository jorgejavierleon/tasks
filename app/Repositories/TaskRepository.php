<?php


namespace App\Repositories;


use App\Task;
use Illuminate\Http\Request;

class TaskRepository
{
    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return Task::find($id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return Task::with('priority', 'users')->get();
    }

    /**
     * @param Request $request
     * @return static
     */
    public function create(Request $request)
    {
        $fields = $this->getFieldsFromRequest($request);
        return Task::create($fields);
    }

    /**
     * @param Request $request
     * @param Task $task
     * @return bool|int
     */
    public function update(Request $request, Task $task)
    {
        $fields = $this->getFieldsFromRequest($request);
        return $task->update($fields);
    }

    /**
     * @param Task $task
     * @return bool|null
     */
    public function delete(Task $task)
    {
        return $task->delete();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return Task::$rules;
    }

    /**
     * @param $request
     * @return array
     */
    private function getFieldsFromRequest($request)
    {
        return [
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'due_date' => $request->input('due_date'),
        ];
    }
}