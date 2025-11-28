<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['lobby', 'join']);
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
        return view('meetings.lobby', compact('meeting'));
    }

    public function join(Meeting $meeting)
    {
        if (!$meeting->is_active) {
            abort(404, 'Meeting not found or ended');
        }

        return view('meetings.room', compact('meeting'));
    }
}