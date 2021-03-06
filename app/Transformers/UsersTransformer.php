<?php


namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UsersTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'tasks'
    ];

    /**
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        $created_at = $user->created_at ?
            $user->created_at->toDateTimeString() :
            null;

        $updated_at = $user->created_at ?
            $user->created_at->toDateTimeString() :
            null;

        return [
            'id'        => (int) $user->id,
            'firstname' => $user->firstname,
            'lastname'  => $user->lastname,
            'email'     => $user->email,
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ];
    }

    public function includeTasks(User $user)
    {
        return $this->collection($user->tasks, new TasksTransformer());
    }
}
