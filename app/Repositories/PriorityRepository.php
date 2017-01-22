<?php


namespace App\Repositories;

use App\Priority;
use Illuminate\Http\Request;

class PriorityRepository
{
    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return Priority::find($id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return Priority::with('tasks')->get();
    }

    /**
     * @param Request $request
     * @return static
     */
    public function create(Request $request)
    {
        $fields = $this->getFieldsFromRequest($request);
        return Priority::create($fields);
    }

    /**
     * @param Request $request
     * @param Priority $priority
     * @return bool|int
     */
    public function update(Request $request, Priority $priority)
    {
        $fields = $this->getFieldsFromRequest($request);
        return $priority->update($fields);
    }

    /**
     * @param Priority $priority
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Priority $priority)
    {
        return $priority->delete();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return Priority::$rules;
    }

    /**
     * @param $request
     * @return array
     */
    private function getFieldsFromRequest($request)
    {
        return ['name' => $request->input('name')];
    }
}
