<?php


namespace App\Repositories;

use App\User;
use Illuminate\Http\Request;

class UserRepository
{
    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return User::find($id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return User::all();
    }

    /**
     * @param Request $request
     * @return static
     */
    public function create(Request $request)
    {
        $fields = $this->getFieldsFromRequest($request);
        return User::create($fields);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return bool|int
     */
    public function update(Request $request, User $user)
    {
        $fields = $this->getFieldsFromRequest($request);
        $fields = array_except($fields, 'password');
        return $user->update($fields);
    }

    public function delete(User $user)
    {
        return $user->delete();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return User::$rules;
    }

    /**
     * @param $request
     * @return array
     */
    private function getFieldsFromRequest($request)
    {
        return [
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];
    }

}