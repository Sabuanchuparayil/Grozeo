<?php

namespace BackOffice\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BaseModel extends Model
{
    
     public static function boot()
     {
        parent::boot();
       
        static::updating(function($model)
        {
            
            $model->fsto_updateon = Carbon::now() ;
        });        
    }
    
}