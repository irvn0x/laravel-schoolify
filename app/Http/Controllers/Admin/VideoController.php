<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:videos.index|videos.create|videos.edit|videos.delete']);
    }


    public function index()
    {
        $videos = Video::latest()->when(request()->q, function ($videos) {
            $videos = $videos->where('name', 'like', '%' . request()->q . '%');
        })->paginate(10);

        return view('admin.video.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.video.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'embed' => 'required'
        ]);

        $video = Video::create([
            'title' => $request->input('title'),
            'embed' => $request->input('embed')
        ]);

        if ($video) {
            //redirect dengan pesan sukses
            return redirect()->route('admin.video.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('admin.video.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }


    public function edit(Video $video)
    {
        return view('admin.video.edit', compact('video'));
    }


    public function update(Request $request, Video $video)
    {
        $this->validate($request, [
            'title' => 'required',
            'embed' => 'required'
        ]);

        $video = Video::findOrFail($video->id);
        $video->update([
            'title' => $request->input('title'),
            'embed' => $request->input('embed')
        ]);

        if ($video) {
            //redirect dengan pesan sukses
            return redirect()->route('admin.video.index')->with(['success' => 'Data Berhasil Diupdate!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('admin.video.index')->with(['error' => 'Data Gagal Diupdate!']);
        }
    }

    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        $video->delete();

        if ($video) {
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
