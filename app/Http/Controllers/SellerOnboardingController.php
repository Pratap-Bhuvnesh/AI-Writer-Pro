<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\SellerProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SellerOnboardingController extends Controller
{
    public function create()
    {
        return view('seller.apply');
    }

    public function store(Request $request)
    {
        $rules = [
            'store_name' => ['required', 'string', 'max:255'],
            'business_type' => ['required', 'in:individual,business,brand,wholesaler'],
            'contact_phone' => ['required', 'string', 'max:40'],
            'city' => ['required', 'string', 'max:120'],
            'state' => ['required', 'string', 'max:120'],
            'address' => ['required', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];

        if (! Auth::check()) {
            $rules = array_merge([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ], $rules);
        }

        $validated = $request->validate($rules);

        $user = Auth::user();

        if (! $user) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['contact_phone'],
                'is_active' => true,
            ]);

            $customerRole = Role::firstOrCreate(['name' => 'customer'], ['guard_name' => 'web']);
            $user->roles()->syncWithoutDetaching([$customerRole->id]);
            Auth::login($user);
        }

        SellerProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'store_name' => $validated['store_name'],
                'business_type' => $validated['business_type'],
                'contact_phone' => $validated['contact_phone'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'address' => $validated['address'],
                'description' => $validated['description'] ?? null,
                'status' => 'pending',
                'approved_at' => null,
            ]
        );

        return redirect()->route('seller.apply')->with('success', 'Seller application submitted. Admin approval is required before you can add products.');
    }
}
