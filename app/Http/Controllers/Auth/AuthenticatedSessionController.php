<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }


    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // First authenticate email + password
        $request->authenticate();

        // Get logged-in user
        $user = Auth::user();

        /*
         * Every employee login account should be linked
         * to an employee record.
         */
        if (!$user->employee_id) {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'This account is not linked to an employee.',
            ]);
        }


        // Get employee record
        $employee = $user->employee;


        /*
         * Block login if:
         * - employee record does not exist
         * - employee status is Inactive
         */
        if (!$employee || $employee->status !== 'Active') {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Your employee account is inactive. Please contact the administrator.',
            ]);
        }


        // Regenerate session after successful login
        $request->session()->regenerate();


        return redirect()->intended(
            route('dashboard', absolute: false)
        );
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}