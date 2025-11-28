<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ParticipantController extends Controller
{
    public function updateStatus(Request $request, Meeting $meeting)
    {
        $sessionId = session()->getId();
        $userSession = session('meeting_' . $meeting->slug);
        
        if (!$userSession) {
            return response()->json(['error' => 'Not authorized'], 403);
        }
        
        $participants = Cache::get("meeting.{$meeting->slug}.participants", []);
        
        if (isset($participants[$sessionId])) {
            if ($request->has('is_muted')) {
                $participants[$sessionId]['is_muted'] = $request->boolean('is_muted');
                session(['meeting_' . $meeting->slug . '.is_muted' => $request->boolean('is_muted')]);
            }
            
            if ($request->has('is_video_off')) {
                $participants[$sessionId]['is_video_off'] = $request->boolean('is_video_off');
                session(['meeting_' . $meeting->slug . '.is_video_off' => $request->boolean('is_video_off')]);
            }
            
            Cache::put("meeting.{$meeting->slug}.participants", $participants, 3600);
        }
        
        return response()->json(['status' => 'updated']);
    }
    
    public function list(Meeting $meeting)
    {
        $participants = Cache::get("meeting.{$meeting->slug}.participants", []);
        
        // Clean up old participants (inactive for more than 5 minutes)
        $activeParticipants = [];
        foreach ($participants as $sessionId => $participant) {
            if ($participant['last_seen']->diffInMinutes(now()) < 5) {
                $activeParticipants[$sessionId] = $participant;
            }
        }
        
        Cache::put("meeting.{$meeting->slug}.participants", $activeParticipants, 3600);
        
        return response()->json(['participants' => array_values($activeParticipants)]);
    }
}