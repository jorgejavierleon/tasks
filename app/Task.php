<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * The rules for validation.
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required',
        'description' => 'required',
        'due_date' => 'required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'description', 'due_date'];

    /**
     * No automatic timestamsps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['due_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function priority()
    {
        return $this->belongsTo('App\Priority');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}