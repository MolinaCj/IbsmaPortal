<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolCalendar;
use Illuminate\Support\Facades\Storage;

class SchoolCalendarController extends Controller
{
    public function index()
    {
        $schoolCalendars = SchoolCalendar::all();
        return view('admin/schoolCalendar', compact('schoolCalendars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'semester' => 'required|string|max:255',
            'sy' => 'required|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:20480',
            'pdf' => 'nullable|mimes:pdf|max:20480', // Allow PDF uploads
            'word' => 'nullable|mimes:doc,docx|max:20480',
        ]);

        // Delete the most recent record if it exists
        $latestRecord = SchoolCalendar::latest()->first();
        if ($latestRecord) {
            $latestRecord->delete();
        }

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('school_calendars', 'public');
            $data['image'] = $imagePath;
        }

        // Handle PDF upload
        if ($request->hasFile('pdf')) {
            $pdfPath = $request->file('pdf')->store('school_calendars', 'public');
            $data['pdf'] = $pdfPath;
        }

        // Handle WORD upload
        if ($request->hasFile('word')) {
            $wordPath = $request->file('word')->store('school_calendars', 'public');
            $data['word'] = $wordPath;
        }


        // Store the new record
        SchoolCalendar::create($data);

        return redirect()->route('schoolCalendar.store')->with('success', 'School Calendar updated successfully.');
    }


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'semester' => 'required|string|max:255',
    //         'sy' => 'required|max:255',
    //         'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:20480'
    //     ]);

    //     $data = $request->all();

    //     if ($request->hasFile('image')) {
    //         $imagePath = $request->file('image')->store('school_calendars', 'public');
    //         $data['image'] = $imagePath;
    //     }

    //     SchoolCalendar::create($data);
    //     return redirect()->route('schoolCalendar.store')->with('success', 'School Calendar   added successfully.');
    // }

    public function update(Request $request, SchoolCalendar $schoolCalendar)
    {
        $request->validate([
            'semester' => 'required|string|max:255',
            'sy' => 'required|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
        ]);

        $data = $request->only(['semester', 'sy']);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($schoolCalendar->image) {
                Storage::disk('public')->delete($schoolCalendar->image);
            }

            // Store new image
            $imagePath = $request->file('image')->store('school_calendars', 'public');
            $data['image'] = $imagePath;
        }

        $schoolCalendar->update($data);

        return redirect()->route('admin.schoolCalendar')->with('success', 'School Calendar updated successfully.');
    }

    public function destroy(SchoolCalendar $schoolCalendar)
    {
        if ($schoolCalendar->image) {
            Storage::disk('public')->delete($schoolCalendar->image);
        }

        $schoolCalendar->delete();
        return redirect()->route('admin.announcements')->with('success', 'Announcement deleted successfully.');
    }
}
