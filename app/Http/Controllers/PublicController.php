<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class PublicController extends Controller
{
    // profile view
    public function profile()
    {
        $user = auth()->user();
        return view('Profile', compact('user'));
    }

    // add student view
    public function addstudent()
    {
        return view('ManageStudent/addstudent');
    }

    // manuals view
    public function manuals()
    {
        return view('manuals');
    }

    // user update personal profile
    public function updatePersonal(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if (!$user) {
            return redirect()->route('Profile')->with('error', 'User not found.');
        }

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        return redirect()->route('Profile')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'oldPassword' => 'required|string',
            'newPassword' => 'required|string|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->oldPassword, $user->password)) {
            return redirect()->back()->withErrors(['oldPassword' => 'The old password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->newPassword),
        ]);

        return redirect()->route('Profile')->with('success', 'Password updated successfully.');
    }

    public function userUploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/users_images'), $imageName);

            // save image path to database
            $user = User::find($request->user_id);
            $user->image = 'uploads/users_images/' . $imageName;
            $user->save();

            return response()->json(['success' => true, 'imageUrl' => asset($user->image)]);
        }

        return response()->json(['success' => false]);
    }

    public function parentUploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('../../uploads/parent_images'), $imageName);

            // Save image path to database
            $parent = ParentList::find($request->parent_id);
            $parent->image = '../../uploads/parent_images/' . $imageName;
            $parent->save();

            return response()->json(['success' => true, 'imageUrl' => asset($parent->image)]);
        }

        return response()->json(['success' => false]);
    }
}
