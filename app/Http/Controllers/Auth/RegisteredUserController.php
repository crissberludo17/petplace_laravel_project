<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        // Common validation
        $request->validate([
            'first_name'   => ['required', 'string', 'max:255'],
            'last_name'    => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'     => ['required', 'confirmed', Rules\Password::defaults()],
            'role'         => ['required', 'in:vet,owner'],
        ]);

        // Vet-specific validation
        if ($request->role === 'vet') {
            $request->validate([
                'license_number' => ['required', 'string', 'max:255'],
                'specialization' => ['nullable', 'string', 'max:255'],
                'clinic_name'    => ['nullable', 'string', 'max:255'],
            ]);
        }

        // Owner-specific validation
        if ($request->role === 'owner') {
            $request->validate([
                'phone'    => ['nullable', 'string', 'max:20'],
                'pet_name' => ['nullable', 'string', 'max:255'],
                'pet_type' => ['nullable', 'string', 'max:255'],
            ]);
        }

        // Create user
        $user = User::create([
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'name'           => $request->first_name . ' ' . $request->last_name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'role'           => $request->role,

            // Vet fields
            'license_number' => $request->role === 'vet' ? $request->license_number : null,
            'specialization' => $request->role === 'vet' ? $request->specialization : null,
            'clinic_name'    => $request->role === 'vet' ? $request->clinic_name : null,

            // Owner fields
            'phone'          => $request->role === 'owner' ? $request->phone : null,
            'pet_name'       => $request->role === 'owner' ? $request->pet_name : null,
            'pet_type'       => $request->role === 'owner' ? $request->pet_type : null,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect based on role
        if ($user->role === 'vet') {
            return redirect()->route('vet.dashboard');
        }

        return redirect()->route('owner.dashboard');

        return redirect()->route('dashboard');
    }
}