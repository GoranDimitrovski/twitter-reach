<?php namespace App;

use Illuminate\Database\Eloquent\Model;


class Reach extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reach';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['reach', 'updated_at'];
    
}
