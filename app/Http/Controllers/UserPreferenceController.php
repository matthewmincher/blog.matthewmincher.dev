<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserPreferencesRequest;
use App\Models\User;
use App\Models\UserPreference;
use App\Repositories\UserPreferenceRepository;

class UserPreferenceController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(UserPreference::class);
    }

    public function store(StoreUserPreferencesRequest $request, User $user, UserPreferenceRepository $preferenceRepository)
    {
        $validated = $request->validated();

        foreach($validated as $key => $value){
            $preferenceRepository->set($user, $key, $value);
        }

        return redirect()->route('users.show', ['user' => $user]);
    }
}
