<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function allUsers()
    {
        $users = User::where('role_id', '!=', 1)
        ->where('is_deleted', '!=', true)
        ->get();  
    
        if ($users->isEmpty()) {
            return view('admin.users')->with('message', 'No users found.');
        }
    
        return view('admin.users', compact('users'));
    }
    
    public function blockUser($id)
    {
        $user = User::findOrFail($id);
        $user->is_blocked = true;
        $user->save();

        return redirect()->route('allUsers')->with('success', 'User has been blocked.');
    }

    public function unblockUser($id)
    {
        $user = User::findOrFail($id);
        $user->is_blocked = false;
        $user->save();

        return redirect()->route('allUsers')->with('success', 'User has been unblocked.');
    }

    public function deleteUser($id)
    {   
        $user = User::findOrFail($id);
        $user->username = 'anon_' . $user->user_id;
        $user->email = 'anon_' . $user->user_id . '@example.com';
        $user->name = 'Anonymous';
        $user->phone = null; 
        $user->profile_photo = null;
        $user->is_deleted = true; 

    $user->save();

        return redirect()->route('allUsers')->with('success', 'User has been deleted.');
    }
}
