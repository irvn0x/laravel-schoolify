<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:slider.index|sliders.create|sliders.delete']);
    }

    public function index()
    {
        $sliders = Slider::latest()->when(request()->q, function ($sliders) {
            $sliders = $sliders->where('title', 'like', '%' . request()->q . '%');
        })->paginate(10);

        return view('admin.slider.index', compact('sliders'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'image'     => 'required|image',
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/sliders', $image->hashName());

        $slider = Slider::create([
            'image'     => $image->hashName(),
        ]);

        if ($slider) {
            //redirect dengan pesan sukses
            return redirect()->route('admin.slider.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('admin.slider.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        $image = Storage::disk('local')->delete('public/sliders/' . basename($slider->image));
        $slider->delete();

        if ($slider) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
}
