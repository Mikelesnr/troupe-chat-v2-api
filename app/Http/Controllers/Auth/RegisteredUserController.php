<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
// use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Http\Resources\UserResource;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $origin = $request->headers->get('origin') ?? $request->headers->get('referer');
        $isFrontend = Str::startsWith($origin, config('app.frontend_url'));

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', Rule::in(['admin', 'moderator', 'trouper'])],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'avatar_url' => '/images/Profile.svg',
            'email_verified_at' => now(), // Skip verification for now
        ]);

        // Leaving this in for future VPS deployment
        // event(new Registered($user));

        Auth::login($user);

        if ($isFrontend) {
            return response()->json([
                'message' => 'User registered and logged in successfully',
                'user' => new UserResource($user),
            ], 201);
        }

        return redirect(route('dashboard'));
    }
}
