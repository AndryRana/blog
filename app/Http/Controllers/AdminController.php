<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
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
        $picName = time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads'),$picName);
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
        if(!$hasFullPath){
            $filePath = public_path().'/uploads/'.$fileName;
        }
        if(file_exists($filePath)){
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
            'email' => 'bail|required|email',
            'password' => 'bail|required|min:6',
            'userType' => 'required',
        ]);
        $password = bcrypt($request->password);
        $user = User::create([
            'fullName' =>$request->fullName,
            'email' =>$request->email,
            'password' =>$password,
            'userType' =>$request->userType,
        ]);
        return $user;
    }

    public function getUsers()
    {
        return User::where('userType', '!=', 'User')->get();
    }
}
