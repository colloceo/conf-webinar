<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['lobby', 'join', 'storeLobbyData']);
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
        // Handle both authenticated and anonymous users
        if (Auth::check()) {
            // Authenticated user (meeting creator)
            session(['meeting_' . $meeting->slug => [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'user_email' => Auth::user()->email,
                'joined_at' => now(),
                'is_muted' => false,
                'is_video_off' => false
            ]]);
        } else {
            // Anonymous user - will set session data after name input
            // Just show the lobby for now
        }
        
        return view('meetings.lobby', compact('meeting'));
    }
    
    public function storeLobbyData(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'participant_name' => 'required|string|max:255'
        ]);
        
        // Store anonymous participant session data
        session(['meeting_' . $meeting->slug => [
            'user_id' => null,
            'user_name' => $validated['participant_name'],
            'user_email' => null,
            'joined_at' => now(),
            'is_muted' => false,
            'is_video_off' => false
        ]]);
        
        return response()->json(['success' => true]);
    }

    public function join(Meeting $meeting)
    {
        // Ensure user session exists for this meeting
        if (!session()->has('meeting_' . $meeting->slug)) {
            return redirect()->route('meetings.lobby', $meeting);
        }

        return view('meetings.room', compact('meeting'));
    }
}