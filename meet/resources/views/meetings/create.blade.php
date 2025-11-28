@extends('layouts.app')

@section('title', 'Create Meeting - Digital Leap Africa')
@section('meta_description', 'Create a new video conference meeting for your community or team.')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div style="text-align: center; margin-bottom: 2rem;">
        <div style="font-size: 3rem; color: var(--cyan-accent); margin-bottom: 1rem;">
            <i class="fas fa-video"></i>
        </div>
        <h1 style="margin-bottom: 0.5rem; color: var(--diamond-white);">Create New Meeting</h1>
        <p style="color: var(--cool-gray);">Set up a video conference for your community</p>
    </div>
    
    <form action="{{ route('meetings.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="title" class="form-label">
                <i class="fas fa-heading"></i> Meeting Title
            </label>
            <input type="text" id="title" name="title" class="form-control" required placeholder="Digital Leap Africa Community Call">
        </div>
        
        <div class="form-group">
            <label for="description" class="form-label">
                <i class="fas fa-align-left"></i> Description (Optional)
            </label>
            <textarea id="description" name="description" class="form-control" placeholder="Brief description of the meeting agenda..."></textarea>
        </div>
        
        <div class="form-group">
            <label for="scheduled_at" class="form-label">
                <i class="fas fa-calendar-alt"></i> Schedule for Later (Optional)
            </label>
            <input type="datetime-local" id="scheduled_at" name="scheduled_at" class="form-control">
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.1rem;">
            <i class="fas fa-plus-circle"></i> Create Meeting
        </button>
    </form>
</div>
@endsection