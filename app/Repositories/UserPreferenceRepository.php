<?php

namespace App\Repositories;

use App\Models\User;


class UserPreferenceRepository
{
    public function get(User $user, $key, $default = null){
        $matchingPreference = $user->preferences->firstWhere('key', '=', $key);

        return $matchingPreference->value ?? $default;
    }
    public function set(User $user, $key, $value){
        if($user->relationLoaded('preferences')){
            $matchingPreference = $user->preferences->firstWhere('key', '=', $key);
        } else {
            $matchingPreference = $user->preferences()->firstWhere('key', '=', $key);
        }

        if($matchingPreference){
            $matchingPreference->value = $value;
            $matchingPreference->save();
        } else {
            $user->preferences()->create(['key' => $key, 'value' => $value]);

            if($user->relationLoaded('preferences')){
                $user->unsetRelation('preferences');
            }
        }
    }
}
