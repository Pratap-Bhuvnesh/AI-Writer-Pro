<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class FortifyServiceProvider extends ServiceProvider
{
    public function register()
    {
       //
    }

    public function boot()
    { 
        /* Fortify::createUsersUsing(function ($input) {
            return User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);
        });

        Fortify::authenticateUsing(function ($request) {
            $user = User::where('email', $request->email)->first();
            
            if ($user && Hash::check($request->password, $user->password)) {
                // Optional: Delete old tokens for security
                $user->tokens()->delete();
                // Create new token
                $token = $user->createToken('api-token')->plainTextToken;

                // Attach token to user model (for response)
                $user->api_token = $token;
                return $user;
            }
        }); */
    }
}