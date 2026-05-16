<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cart;
use App\Services\BrevoMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthController extends Controller
{
    protected BrevoMailer $brevoMailer;

    public function __construct(BrevoMailer $brevoMailer)
    {
        $this->brevoMailer = $brevoMailer;
    }

    public function showLoginForm(): View|RedirectResponse
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $loginAction = request()->routeIs('admin.login') ? route('admin.login.store') : route('login.store');

        return view('auth.login', compact('loginAction'));
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            $guestCartData = json_decode($request->input('guest_cart', '[]'), true) ?? [];
            $this->mergeGuestCart($user, $guestCartData);

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            // If user has items in cart after merging, redirect to checkout
            if ($user->carts()->count() > 0) {
                return redirect()->route('checkout.index');
            }

            return redirect()->intended(route('home'));
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->onlyInput('email');
    }

    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        $this->brevoMailer->sendRegistrationSuccess($user);

        return redirect()->route('home');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    protected function mergeGuestCart(User $user, array $guestCartData = []): void
    {
        if (empty($guestCartData)) {
            return;
        }

        foreach ($guestCartData as $item) {
            if (!isset($item['product_id'])) {
                continue;
            }

            $existingCart = Cart::where('user_id', $user->id)
                ->where('product_id', $item['product_id'])
                ->where('size', $item['size'] ?? null)
                ->where('color', $item['color'] ?? null)
                ->first();

            if ($existingCart) {
                $existingCart->increment('quantity', $item['quantity'] ?? 1);
            } else {
                Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $item['product_id'],
                    'size' => $item['size'] ?? null,
                    'color' => $item['color'] ?? null,
                    'quantity' => $item['quantity'] ?? 1,
                ]);
            }
        }
    }
}