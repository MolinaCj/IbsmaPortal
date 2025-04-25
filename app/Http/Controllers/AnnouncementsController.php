<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AnnouncementImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementsController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->take(10)->get();

        return view('admin/announcements', compact('announcements'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|max:255',
            'images' => 'nullable|array', // Ensure images is an array
            'images.*' => 'image|mimes:jpg,jpeg,png,gif|max:20480',
            'pdf' => 'nullable|mimes:pdf|max:20480',
            'word' => 'nullable|mimes:doc,docx|max:20480',
        ]);

        // Create the announcement first
        $announcement = Announcement::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // Store Word and PDF
        if ($request->hasFile('pdf')) {
            $pdfPath = $request->file('pdf')->store('announcements/files', 'public');
            $announcement->update(['pdf' => $pdfPath]);
        }

        if ($request->hasFile('word')) {
            $wordPath = $request->file('word')->store('announcements/files', 'public');
            $announcement->update(['word' => $wordPath]);
        }

        // Save multiple images with the same announcement_id
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) { // Ensure the file is valid
                    $imagePath = $image->store('announcements', 'public');
                    $announcement->images()->create(['image' => $imagePath]);
                }
            }
        }

        return redirect()->route('admin.announcements')->with('success', 'Announcement added successfully.');
    }
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'title' => 'required|string|max:255',
    //         'description' => 'required|max:255',
    //         'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:20480'
    //     ]);

    //     $data = $request->all();

    //     if ($request->hasFile('image')) {
    //         $imagePath = $request->file('image')->store('announcements', 'public');
    //         $data['image'] = $imagePath;
    //     }

    //     Announcement::create($data);
    //     return redirect()->route('announcements.store')->with('success', 'Announcement added successfully.');
    // }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        if ($announcement->image) {
            Storage::disk('public')->delete($announcement->image);
        }

        $announcement->delete();
        return redirect()->route('admin.announcements')->with('deleted', 'Announcement deleted successfully.');

    }
}
