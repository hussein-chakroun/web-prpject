<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }
    
    
    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
{
    // Ensure the user is authenticated
    $user = Auth::user();

    if (!($user instanceof \App\Models\User)) {
        return redirect()->back()->withErrors(['Invalid user model.']);
    }

    switch ($request->input('type')) {
        case 'email':
            $request->validate([
                'email' => 'required|email|unique:users,email,' . $user->id,
            ]);

            $user->email = $request->email;
            $user->save();

            return redirect()->back()->with('success', 'Email updated successfully.');

        case 'password':
            $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user->password = bcrypt($request->password);
            $user->save();

            return redirect()->back()->with('success', 'Password updated successfully.');

        default:
            return redirect()->back()->withErrors(['Invalid update type.']);
    }
}

    
    
    
    

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
