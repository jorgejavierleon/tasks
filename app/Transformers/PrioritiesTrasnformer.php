<?php


namespace App\Transformers;


use App\Priority;
use League\Fractal\TransformerAbstract;

class PrioritiesTrasnformer extends TransformerAbstract
{
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
}