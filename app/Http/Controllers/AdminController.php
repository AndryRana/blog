<?php

namespace App\Http\Controllers;

use App\Models\Tag;
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
}
