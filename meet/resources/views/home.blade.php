@extends('layouts.app')

@section('title', 'Digital Leap Africa - Video Conferencing Platform')
@section('meta_description', 'Low-bandwidth video conferencing designed for African communities. Connect, collaborate, and grow together with optimized technology.')

@section('content')
<div style="text-align: center; padding: 2rem 0;">
    <div style="color: white; margin-bottom: 60px;">
        <h1 style="font-size: 3.5rem; margin-bottom: 20px; font-weight: 700;">Digital Leap Africa</h1>
        <p style="font-size: 1.2rem; opacity: 0.9; max-width: 600px; margin: 0 auto;">Low-bandwidth video conferencing designed for African communities. Connect, collaborate, and grow together with optimized technology.</p>
    </div>
    
    <div style="display: flex; gap: 20px; justify-content: center; margin-bottom: 80px; flex-wrap: wrap;">
        @auth
            <a href="{{ route('meetings.create') }}" class="btn btn-primary">
                <i class="fas fa-video"></i> Start New Meeting
            </a>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-outline">
                    <i class="fas fa-sign-out-alt"></i> Logout ({{ Auth::user()->name }})
                </button>
            </form>
        @else
            <a href="{{ route('auth.google') }}" class="btn btn-primary">
                <i class="fab fa-google"></i> Login with Google to Start Meeting
            </a>
        @endauth
        <button onclick="joinMeeting()" class="btn btn-outline">
            <i class="fas fa-phone"></i> Join Meeting
        </button>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
        <div class="card">
            <div style="font-size: 2.5rem; margin-bottom: 15px; color: var(--cyan-accent);">
                <i class="fas fa-signal"></i>
            </div>
            <h3 style="color: var(--diamond-white); margin-bottom: 15px; font-size: 1.3rem;">Low Bandwidth Optimized</h3>
            <p style="color: var(--cool-gray); line-height: 1.6;">Automatically adjusts video quality based on your connection. Works great even on 2G/3G networks.</p>
        </div>
        
        <div class="card">
            <div style="font-size: 2.5rem; margin-bottom: 15px; color: var(--cyan-accent);">
                <i class="fas fa-users"></i>
            </div>
            <h3 style="color: var(--diamond-white); margin-bottom: 15px; font-size: 1.3rem;">Community Tools</h3>
            <p style="color: var(--cool-gray); line-height: 1.6;">Hand raising queue, shared notes, and real-time polls designed for community collaboration.</p>
        </div>
        
        <div class="card">
            <div style="font-size: 2.5rem; margin-bottom: 15px; color: var(--cyan-accent);">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h3 style="color: var(--diamond-white); margin-bottom: 15px; font-size: 1.3rem;">Secure & Private</h3>
            <p style="color: var(--cool-gray); line-height: 1.6;">End-to-end encrypted peer-to-peer connections. No data stored on external servers.</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function joinMeeting() {
        const meetingId = prompt('Enter Meeting ID:');
        if (meetingId) {
            window.location.href = `/meetings/${meetingId}/lobby`;
        }
    }
</script>
@endpush
@endsection