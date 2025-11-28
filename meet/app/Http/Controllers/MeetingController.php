<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        return view('meetings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'nullable|date|after:now'
        ]);

        $meeting = Meeting::create([
            ...$validated,
            'host_id' => Auth::id(),
            'settings' => [
                'max_participants' => 10,
                'enable_chat' => true,
                'enable_screen_share' => true,
                'data_saver_default' => false
            ]
        ]);

        return redirect()->route('meetings.lobby', $meeting);
    }

    public function lobby(Meeting $meeting)
    {
        // Store user session for this meeting
        session(['meeting_' . $meeting->slug => [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'joined_at' => now(),
            'is_muted' => false,
            'is_video_off' => false
        ]]);
        
        return view('meetings.lobby', compact('meeting'));
    }

    public function join(Meeting $meeting)
    {
        if (!$meeting->is_active) {
            abort(404, 'Meeting not found or ended');
        }

        // Ensure user session exists for this meeting
        if (!session()->has('meeting_' . $meeting->slug)) {
            return redirect()->route('meetings.lobby', $meeting);
        }

        return view('meetings.room', compact('meeting'));
    }
}