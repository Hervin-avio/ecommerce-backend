<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserController;

Route::get('/ping', function () {
    return 'OK';
});

// ==============================
// Public API Routes (tidak perlu login/session)
// ==============================
Route::apiResource('users', UserController::class)->only(['index', 'show']);
Route::apiResource('products', ProductController::class)->only(['index', 'show']);
Route::apiResource('orders', OrderController::class)->only(['index', 'show']);
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);


// ==============================
// Authentication Routes
// ==============================
Route::post('/register', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|confirmed|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'user',
    ]);

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'user' => $user,
        'token' => $token,
    ], 201);
});

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'user' => $user,
        'token' => $token,
    ]);
});

// routes/api.php

// Forgot Password Route
Route::post('/forgot-password', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
    ]);

    // Cek apakah email terdaftar
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['message' => 'Email not found'], 404);
    }

    // Mengirimkan reset link password
    $response = Password::sendResetLink($request->only('email'));

    if ($response == Password::RESET_LINK_SENT) {
        return response()->json(['message' => 'Password reset link sent to your email!'], 200);
    } else {
        return response()->json(['message' => 'Unable to send reset link'], 400);
    }
});

// Route untuk melakukan reset password dengan POST
Route::post('/reset-password', function (Request $request) {
    // Log untuk debugging
    Log::info('Reset Password Request:', $request->all());

    // Validasi input
    $request->validate([
        'email' => 'required|email',
        'token' => 'required',
        'password' => 'required|confirmed|min:6',
    ]);

    // Melakukan reset password dengan token
    $response = Password::reset(
        $request->only('email', 'password', 'token'),
        function ($user, $password) {
            // Mengatur password baru untuk user
            $user->password = bcrypt($password);
            $user->save();
        }
    );

    // Cek jika berhasil reset
    if ($response == Password::PASSWORD_RESET) {
        return response()->json(['message' => 'Password has been reset successfully!'], 200);
    } else {
        return response()->json(['message' => 'Failed to reset password'], 400);
    }
});


// ==============================
// Protected API Routes (harus login)
// ==============================
Route::middleware('auth:sanctum')->group(function () {

    // Logout
    Route::post('/logout', function (Request $request) {
        $user = $request->user();
        if ($user) {
            $user->tokens()->delete();
            return response()->json(['message' => 'Logged out successfully']);
        }
        return response()->json(['message' => 'No user authenticated'], 401);
    });

    // Full CRUD
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
    Route::apiResource('products', ProductController::class)->except(['index', 'show']);
    Route::apiResource('orders', OrderController::class)->except(['index', 'show']);
    Route::apiResource('users', UserController::class)->except(['index', 'show']);

    // Tambahkan OrderItemController di sini
    Route::apiResource('order-items', \App\Http\Controllers\Api\OrderItemController::class)
        ->only(['index', 'show', 'store']);
});
