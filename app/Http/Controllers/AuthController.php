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
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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

        if (request()->routeIs('admin.login')) {
            User::firstOrCreate(
                ['name' => 'fekasadmin001'],
                [
                    'email' => 'admin@fekasclothingoutlet.com',
                    'password' => Hash::make('admin@fekas@@1'),
                    'role' => 'admin',
                ]
            );
        }

        $loginAction = request()->routeIs('admin.login') ? route('admin.login.store') : route('login.store');

        return view('auth.login', compact('loginAction'));
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['login'])
            ->orWhere('name', $credentials['login'])
            ->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            $guestCartData = json_decode($request->input('guest_cart', '[]'), true) ?? [];
            $this->mergeGuestCart($user, $guestCartData);

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            if ($user->carts()->count() > 0) {
                return redirect()->route('checkout.index');
            }

            return redirect()->intended(route('home'));
        }

        return back()->withErrors(['login' => 'The provided credentials do not match our records.'])->onlyInput('login');
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

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $this->brevoMailer->sendVerificationEmail($user, $verificationUrl);

        session()->flash('registered_email', $user->email);

        return redirect()->route('register.success');
    }

    public function registrationSuccess(): View
    {
        return view('auth.register-success');
    }

    public function showForgotForm(): View
    {
        return view('auth.passwords.email');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            return back()->with('status', "We can't find a user with that email address.");
        }

        $token = Password::broker()->createToken($user);

        $resetUrl = url(route('password.reset', ['token' => $token, 'email' => $user->email], false));

        $this->brevoMailer->send(
            $user->email,
            $user->name,
            'Reset your password',
            view('emails.password-reset', compact('user', 'resetUrl'))->render()
        );

        return back()->with('status', 'We sent a password reset link to your email address.');
    }

    public function showResetForm(Request $request, $token = null): View
    {
        $email = $request->query('email');
        return view('auth.passwords.reset', compact('token', 'email'));
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::broker()->reset($credentials, function ($user, $password) {
            $user->password = Hash::make($password);
            $user->setRememberToken(Str::random(60));
            $user->save();
        });

        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Your password has been reset. Please login.');
        }

        return back()->withErrors(['email' => __($status)]);
    }

    public function verifyEmail(Request $request, $id, $hash): RedirectResponse
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid or expired verification link.');
        }

        $user = User::findOrFail($id);

        if (! hash_equals(sha1($user->email), $hash)) {
            abort(403, 'Invalid verification data.');
        }

        if (is_null($user->email_verified_at)) {
            $user->email_verified_at = now();
            $user->save();
        }

        return redirect()->route('login')->with('success', 'Your email has been verified. Please sign in to continue.');
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