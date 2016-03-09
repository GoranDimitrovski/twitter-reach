<?php namespace App;

use Illuminate\Database\Eloquent\Model;


class Tweet extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tweet';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['url', 'tweet_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function reach()
    {
        return $this->hasOne('App\Reach', 'tweet_id');
    }

}
