<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserPreferencesRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Repositories\UserPreferenceRepository;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user, UserPreferenceRepository $preferenceRepository)
    {
        $preferences = [];
        foreach(StoreUserPreferencesRequest::PREFERENCE_KEYS as $preferenceKey){
            $preferences[$preferenceKey] = $preferenceRepository->get($user, $preferenceKey, false);
        }

        return view('users.show', [
            'user' => $user,
            'preferences' => $preferences
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->fill($validated)->save();

        return redirect()->route('users.show', [
            'user' => $user
        ]);
    }
}
