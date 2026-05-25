<?php

namespace App\Http\Controllers;

use App\Models\LoginSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LoginSlideController extends Controller
{
    public function index()
    {
        $slides = LoginSlide::orderBy('order_num', 'asc')->get();
        return view('Admin.LoginSlide.index', compact('slides'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5048',
        ]);

        $slide = new LoginSlide();
        $slide->title = $request->title;
        $slide->description = $request->description;
        $slide->demo_email = $request->demo_email;
        $slide->demo_password = $request->demo_password;
        $slide->order_num = $request->order_num ?? 0;
        $slide->is_active = $request->has('is_active') ? 1 : 0;
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time().'_'.Str::random(5).'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/upload/loginslide');
            
            if(!File::isDirectory($destinationPath)){
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            
            $image->move($destinationPath, $name);
            $slide->image_path = '/upload/loginslide/'.$name;
        }

        $slide->save();

        return redirect()->back()->with('success', 'Slide Login berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5048',
        ]);

        $slide = LoginSlide::findOrFail($id);
        $slide->title = $request->title;
        $slide->description = $request->description;
        $slide->demo_email = $request->demo_email;
        $slide->demo_password = $request->demo_password;
        $slide->order_num = $request->order_num ?? 0;
        $slide->is_active = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('image')) {
            // Delete old image
            if ($slide->image_path && File::exists(public_path($slide->image_path))) {
                File::delete(public_path($slide->image_path));
            }

            $image = $request->file('image');
            $name = time().'_'.Str::random(5).'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/upload/loginslide');
            
            if(!File::isDirectory($destinationPath)){
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            
            $image->move($destinationPath, $name);
            $slide->image_path = '/upload/loginslide/'.$name;
        }

        $slide->save();

        return redirect()->back()->with('success', 'Slide Login berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $slide = LoginSlide::findOrFail($id);
        
        if ($slide->image_path && File::exists(public_path($slide->image_path))) {
            File::delete(public_path($slide->image_path));
        }
        
        $slide->delete();

        return redirect()->back()->with('success', 'Slide Login berhasil dihapus.');
    }
}
