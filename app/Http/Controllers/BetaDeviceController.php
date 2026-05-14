<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\DeviceFingerprint;
use Illuminate\Support\Facades\Hash;

class BetaDeviceController extends Controller
{
    public function checkDevice(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_id' => 'required',
            'device_name' => 'nullable',
            'platform' => 'nullable',
        ]);

        // Find user
        $user = Users::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Check password
        if (!Hash::check($request->password, $user->password)) {

            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Check saved device
        $device = DeviceFingerprint::where('user_id', $user->id)->first();

        // First login
        if (!$device) {

            DeviceFingerprint::create([
                'user_id' => $user->id,
                'device_id' => $request->device_id,
                'device_name' => $request->device_name,
                'platform' => $request->platform,
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User login successful',
                'user' => $user
            ]);
        }

        // Same device
        if ($device->device_id === $request->device_id) {

            return response()->json([
                'success' => true,
                'message' => 'User login successful',
                'user' => $user
            ]);
        }

        // Different device
        return response()->json([
            'success' => false,
            'message' => 'This account is already linked to another device'
        ], 403);
    }
}