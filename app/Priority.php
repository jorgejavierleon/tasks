<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    /**
     * The rules for validation.
     *
     * @var array
     */
    public static $rules = ['name' => 'required'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * No automatic timestamsps.
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany('App\Task');
    }
}