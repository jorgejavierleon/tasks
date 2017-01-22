<?php


namespace App\Transformers;

use App\Priority;
use League\Fractal\TransformerAbstract;

class PrioritiesTrasnformer extends TransformerAbstract
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
     * @param Priority $priority
     * @return array
     */
    public function transform(Priority $priority)
    {
        return [
            'id'   => (int) $priority->id,
            'name' => $priority->name,
        ];
    }

    /**
     * @param Priority $priority
     * @return \League\Fractal\Resource\Collection
     */
    public function includeTasks(Priority $priority)
    {
        return $this->collection($priority->tasks, new TasksTransformer());
    }
}
