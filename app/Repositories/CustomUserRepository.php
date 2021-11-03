<?php

namespace App\Repositories;

use App\Models\User;

use Auth0\Login\Repository\Auth0UserRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Str;

class CustomUserRepository extends Auth0UserRepository
{
    protected function upsertUser( $profile ) {
        $user = User::firstOrCreate(['sub' => $profile['sub']], [
            'email' => $profile['email'] ?? '',
            'name' => $profile['name'] ?? '',
            'picture' => $profile['picture'] ?? null,
            'api_token' => Str::random(60)
        ]);

        $profileIsAdministrator = in_array('Administrator', $profile['http://www.matthewmincher.dev/roles'] ?? [], true);
        if($profileIsAdministrator !== $user->is_author){
            $user->is_author = $profileIsAdministrator;
        }
        if($user->picture !== $profile['picture']){
            $user->picture = $profile['picture'];
        }
        if($user->isDirty()){
            $user->save();
        }

        return $user;
    }

    public function getUserByDecodedJWT(array $decodedJwt) : Authenticatable
    {
        return $this->upsertUser( $decodedJwt );
    }

    public function getUserByUserInfo(array $userinfo) : Authenticatable
    {
        return $this->upsertUser( $userinfo['profile'] );
    }

    public function getUserByIdentifier($identifier): ?Authenticatable
    {
        return User::find($identifier);
    }
}
