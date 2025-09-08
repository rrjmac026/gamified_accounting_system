<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display the login view.
     */
    public function show()
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function authenticate(LoginRequest $request)
    {
        // LoginRequest handles validation and rate limiting
        $request->authenticate();

        // Get authenticated user
        $user = Auth::user();

        // Update last login timestamp
        $user->update([
            'last_login_at' => now()
        ]);

        // Log successful login
        $this->logLoginActivity($user, 'login_success', $request);

        // Regenerate session for security
        $request->session()->regenerate();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Logged in successfully',
                'user' => $user->load(['student', 'instructor']),
            ]);
        }

        // Redirect based on user role
        return match($user->role) {
            'admin' => redirect()->intended('admin/dashboard'),
            'instructor' => redirect()->intended('instructor/dashboard'),
            'student' => redirect()->intended('students/dashboard'),
            default => redirect()->intended('dashboard'),
        };
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        // Log logout activity before destroying session
        if ($user = Auth::user()) {
            $this->logLoginActivity($user, 'logout', $request);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ]);
        }

        return redirect('/');
    }

    /**
     * Log login related activity
     */
    private function logLoginActivity($user, string $action, Request $request): void 
    {
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'model_type' => 'User',
            'model_id' => $user->id,
            'details' => [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()->toISOString(),
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'performed_at' => now(),
        ]);
    }
}
