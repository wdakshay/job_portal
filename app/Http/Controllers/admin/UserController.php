<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.list', compact('users'));
    }

    public function editUser($id)
    {
        $user = User::find($id);

        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id . ',id',
        ]);

        if ($validator->passes()) {

            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->designation = $request->designation;
            $user->mobile = $request->mobile;
            $user->save();

            session()->flash('success', 'User Updated successfully');

            return response()->json(['status' => 'true']);

        } else {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
    }

    public function deleteUser(Request $request)
    {

        $id = $request->id;
        $user = User::find($id);
        if ($user == null) {
            session()->flash('error', 'User Not Found or Deleted already');
            return response()->json(['status' => false, 'message' => 'User Not Found']);
        } else {
            $user->delete();
            session()->flash('success', 'User Deleted successfully');
            return response()->json(['status' => true, 'message' => 'User Deleted successfully']);
        }
    }
}
