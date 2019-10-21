<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class College_model extends Model
{
      public function cost()
    {
    	 return $this->hasOne('App\Phone');
    }
}
