<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        //  first check if you are logged in and admin user ...
        if (!Auth::check() && $request->path() != 'adminlogin') {
            return redirect('/adminlogin');
        }

        if (!Auth::check() && $request->path() == 'adminlogin') {
            return view('home');
        }
        // You are already logged in... so check if you are admin user ...
        $user =  Auth::user();
        if ($user->userType == 'User') {
            return redirect('/adminlogin');
        }

        if ($request->path() == 'adminlogin') {
            return redirect('/');
        }

        return $this->checkForPermission($user, $request);
    }

    public function checkForPermission($user, $request)
    {
        $permission = json_decode($user->role->permission);
        $hasPermission = false;
        if(!$permission)  return view('home');
        foreach ($permission as $p) {
            if ($p->name == $request->path()) {
                if ($p->read) {
                    $hasPermission = true;
                }
            }
        }
        if ($hasPermission) return view('home');
        return view('notfound');

        // echo $permission[0]->name;
        // echo $request->path();
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function addTag(Request $request)
    {
        // validate request
        $request->validate([
            'tagName' => 'required'
        ]);
        return Tag::create([
            'tagName' => $request->tagName
        ]);
    }

    public function editTag(Request $request)
    {
        // validate request
        $request->validate([
            'tagName' => 'required',
            'id' => 'required'
        ]);
        return Tag::where('id', $request->id)->update([
            'tagName' => $request->tagName
        ]);
    }

    public function deleteTag(Request $request)
    {
        // validate request
        $request->validate([
            'tagName' => 'required',
            'id' => 'required'
        ]);
        return Tag::where('id', $request->id)->delete();
    }

    public function getTag()
    {
        return Tag::orderByDesc('id')->get();
    }

    public function upload(Request $request)
    {
        // validate request
        $request->validate([
            'file' => 'required|image|mimes:jpeg,jpg,png',
        ]);
        $picName = time() . '.' . $request->file->extension();
        $request->file->move(public_path('uploads'), $picName);
        return $picName;
    }

    // Upload Image from editor.js
    public function uploadEditorImage(Request $request)
    {
        // validate request
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png',
        ]);
        $picName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('uploads'), $picName);
        return response()->json([
            "success" => 1,
            "file"=> ["url" => "https://blog.test/uploads/$picName"],
           
        ]);
        return $picName;
    }

    public function deleteImage(Request $request)
    {
        $fileName = $request->imageName;
        $this->deleteFileFromServer($fileName, false);
        return 'done';
    }

    public function deleteFileFromServer($fileName, $hasFullPath = false)
    {
        if (!$hasFullPath) {
            $filePath = public_path() . '/uploads/' . $fileName;
        }
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
        return;
    }

    public function addCategory(Request $request)
    {
        // validate request
        $request->validate([
            'categoryName' => 'required',
            'iconImage' => 'required'
        ]);
        return Category::create([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage
        ]);
    }

    public function getCategory()
    {
        return Category::orderByDesc('id')->get();
    }

    public function editCategory(Request $request)
    {
        // validate request
        $request->validate([
            'categoryName' => 'required',
            'iconImage' => 'required',
            'id' => 'required'
        ]);
        return Category::where('id', $request->id)->update([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage
        ]);
    }

    public function deleteCategory(Request $request)
    {
        // first delete the original file from the server
        $this->deleteFileFromServer($request->iconImage);
        // validate request
        $request->validate([
            'id' => 'required'
        ]);
        return Category::where('id', $request->id)->delete();
    }

    public function createUser(Request $request)
    {
        // validate request
        $request->validate([
            'fullName' => 'required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6',
            'role_id' => 'required',
        ]);
        $password = bcrypt($request->password);
        $user = User::create([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'password' => $password,
            'role_id' => $request->role_id,
        ]);
        return $user;
    }

    public function editUser(Request $request)
    {
        // validate request
        $request->validate([
            'fullName' => 'required',
            'email' => 'bail|required|email|unique:users,email,$request->id',
            'password' => 'min:6',
            'role_id' => 'required',
        ]);
        $data = [
            'fullName' => $request->fullName,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ];
        if ($request->password) {
            $password = bcrypt($request->password);
            $data['password'] = $password;
        }
        $user = User::where('id', $request->id)->update($data);
        return $user;
    }


    public function getUsers()
    {
        return User::get();
    }


    public function adminLogin(Request $request)
    {
        // validate request
        $request->validate([
            'email' => 'required|email',
            'password' => 'bail|required|min:6',
        ]);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            if ($user->role->isAdmin == 0) {
                Auth::logout();
                return response()->json([
                    'msg' => 'Incorrect login details',
                ], 401);
            }
            return response()->json([
                'msg' => 'You are logged in',
                'user' => $user
            ]);
        } else {

            return response()->json([
                'msg' => 'Incorrect login details',
            ], 401);
        }
    }


    public function addRole(Request $request)
    {
        // validate request
        $request->validate([
            'roleName' => 'required',
        ]);
        return Role::create([
            'roleName' => $request->roleName
        ]);
    }

    public function editRole(Request $request)
    {
        // validate request
        $request->validate([
            'roleName' => 'required',
            'id' => 'required'
        ]);
        return Role::where('id', $request->id)->update([
            'roleName' => $request->roleName
        ]);
    }

    public function getRoles()
    {
        return Role::all();
    }

    public function assignRole(Request $request)
    {
        // validate request
        $request->validate([
            'permission' => 'required',
            'id' => 'required'
        ]);

        return Role::where('id', $request->id)->update([
            'permission' => $request->permission
        ]);
    }

    public function slug()
    {
        $title = 'this is a nice title';
        Blog::create([
            'title' => $title,
            'post' => 'some post',
            'post_excerpt' => 'excerpt',
            'user_id' => 1,
            'metaDescription' => 'Blog title',
            'metaDescription' => 'Blog post'

        ]);
        return $title;
    }
}
