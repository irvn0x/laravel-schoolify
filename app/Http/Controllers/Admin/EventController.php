<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:events.index|events.create|events.edit|events.delete']);
    }

    public function index()
    {
        $events = Event::latest()->when(request()->q, function ($events) {
            $events = $events->where('title', 'like', '%' . request()->q . '%');
        })->paginate(10);

        return view('admin.event.index', compact('events'));
    }

    public function create()
    {
        return view('admin.event.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title'     => 'required',
            'content'   => 'required',
            'location'  => 'required',
            'date'      => 'required'
        ]);

        $event = Event::create([
            'title'     => $request->input('title'),
            'slug'      => Str::slug($request->input('title'), '-'),
            'content'   => $request->input('content'),
            'location'  => $request->input('location'),
            'date'      => $request->input('date')
        ]);

        if ($event) {
            //redirect dengan pesan sukses
            return redirect()->route('admin.event.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('admin.event.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    public function edit(Event $event)
    {
        return view('admin.event.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $this->validate($request, [
            'title'     => 'required',
            'content'   => 'required',
            'location'  => 'required',
            'date'      => 'required'
        ]);

        $event = Event::findOrFail($event->id);
        $event->update([
            'title'     => $request->input('title'),
            'slug'      => Str::slug($request->input('title'), '-'),
            'content'   => $request->input('content'),
            'location'  => $request->input('location'),
            'date'      => $request->input('date')
        ]);

        if ($event) {
            //redirect dengan pesan sukses
            return redirect()->route('admin.event.index')->with(['success' => 'Data Berhasil Diupdate!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('admin.event.index')->with(['error' => 'Data Gagal Diupdate!']);
        }
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        if ($event) {
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