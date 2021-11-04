<?php

namespace App\Observers\Traits;

use Illuminate\Support\Facades\Cache;

trait InvalidatesTagsTrait {
    public static function bootInvalidatesTagsTrait(){
        $invalidate = function(){
            Cache::tags('tags')->flush();
        };

        static::created($invalidate);
        static::updated($invalidate);
        static::deleted($invalidate);
    }
}
