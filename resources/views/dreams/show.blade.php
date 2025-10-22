<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $dream->heading }} - AmoreCanvas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .nav-buttons {
            margin-bottom: 20px;
        }

        .nav-buttons a {
            display: inline-block;
            padding: 10px 20px;
            background: white;
            color: #764ba2;
            text-decoration: none;
            border-radius: 20px;
            font-weight: bold;
        }

        .dream-header {
            background: white;
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .dream-tag {
            display: inline-block;
            padding: 8px 18px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .tag-solo {
            background: #667eea;
            color: white;
        }

        .tag-shared {
            background: #f5576c;
            color: white;
        }

        .tag-planning {
            background: #ffa726;
            color: white;
        }

        .tag-scheduled {
            background: #66bb6a;
            color: white;
        }

        .tag-cherished {
            background: #9c27b0;
            color: white;
        }

        .tag-fulfilled {
            background: #ff6b9d;
            color: white;
        }

        .dream-header h1 {
            color: #764ba2;
            font-size: 36px;
            margin-bottom: 10px;
        }

        .dream-header h2 {
            color: #666;
            font-size: 22px;
            font-style: italic;
            margin-bottom: 25px;
        }

        .dream-detail {
            margin-bottom: 20px;
        }

        .dream-detail .label {
            font-weight: bold;
            color: #555;
            display: block;
            margin-bottom: 8px;
        }

        .dream-detail .content {
            color: #333;
            line-height: 1.8;
            font-size: 16px;
        }

        .dream-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            border: none;
            cursor: pointer;
            font-size: 15px;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .negotiations-section {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .negotiations-section h3 {
            color: #764ba2;
            font-size: 28px;
            margin-bottom: 25px;
        }

        .negotiation-item {
            background: #f8f9fa;
            border-left: 4px solid #764ba2;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 10px;
        }

        .negotiation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .proposer {
            font-weight: bold;
            color: #764ba2;
        }

        .negotiation-date {
            color: #333;
            font-size: 18px;
            margin: 10px 0;
        }

        .negotiation-message {
            color: #555;
            font-style: italic;
            margin: 10px 0;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-pending {
            background: #ffa726;
            color: white;
        }

        .status-accepted {
            background: #66bb6a;
            color: white;
        }

        .status-rejected {
            background: #ef5350;
            color: white;
        }

        .status-edited {
            background: #9e9e9e;
            color: white;
        }

        .status-rescheduled {
            background: #29b6f6;
            color: white;
        }

        .status-remove_requested {
            background: #ff9800;
            color: white;
        }

        .status-remove_confirmed {
            background: #8d6e63;
            color: white;
        }

        .status-missed {
            background: #757575;
            color: white;
        }

        .alert {
            padding: 15px 25px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        form {
            display: inline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            .dream-header {
                padding: 25px 20px;
                border-radius: 15px;
            }

            .dream-header h1 {
                font-size: 28px;
            }

            .dream-header h2 {
                font-size: 18px;
            }

            .dream-tag {
                font-size: 12px;
                padding: 6px 14px;
            }

            .dream-detail .label {
                font-size: 14px;
            }

            .dream-detail .content {
                font-size: 15px;
            }

            .dream-actions {
                flex-direction: column;
                gap: 10px;
            }

            .btn {
                width: 100%;
                padding: 12px 20px;
                font-size: 14px;
            }

            .nav-buttons a {
                padding: 8px 16px;
                font-size: 14px;
            }

            .negotiations-section {
                padding: 25px 20px;
                border-radius: 15px;
            }

            .negotiations-section h3 {
                font-size: 24px;
            }

            .negotiation-item {
                padding: 15px;
            }

            .negotiation-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .negotiation-date {
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .dream-header {
                padding: 20px 15px;
            }

            .dream-header h1 {
                font-size: 24px;
            }

            .dream-header h2 {
                font-size: 16px;
            }

            .dream-tag {
                font-size: 11px;
                padding: 5px 12px;
            }

            .dream-detail .content {
                font-size: 14px;
            }

            .btn {
                padding: 11px 18px;
                font-size: 13px;
            }

            .negotiations-section {
                padding: 20px 15px;
            }

            .negotiations-section h3 {
                font-size: 22px;
            }

            .negotiation-item {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav-buttons">
            @if($dream->status === 'scheduled' || $dream->status === 'cherished')
                <a href="{{ route('bucket-list.index') }}">← Back to Bucket List</a>
            @else
                <a href="{{ route('dreams.index') }}">← Back to Shared Dreams</a>
            @endif
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info">
                {{ session('info') }}
            </div>
        @endif

        <div class="dream-header">
            @if($dream->status === 'solo')
                @if($dream->creator_id === Auth::id())
                    <span class="dream-tag tag-solo">My Dream</span>
                @else
                    <span class="dream-tag tag-solo">{{ $partner->name }}'s Dream</span>
                @endif
            @elseif($dream->status === 'shared')
                <span class="dream-tag tag-shared">Shared Dream</span>
            @elseif($dream->status === 'planning')
                <span class="dream-tag tag-planning">Planning</span>
            @elseif($dream->status === 'scheduled')
                <span class="dream-tag tag-scheduled">Scheduled</span>
            @elseif($dream->status === 'cherished')
                <span class="dream-tag tag-cherished">Cherished Memory</span>
            @elseif($dream->status === 'fulfilled')
                <span class="dream-tag tag-fulfilled">Fulfilled</span>
            @endif

            <h1>{{ $dream->heading }}</h1>
            
            @if($dream->title)
                <h2>{{ $dream->title }}</h2>
            @endif

            <div class="dream-detail">
                <span class="label">📖 Description</span>
                <p class="content">{{ $dream->description }}</p>
            </div>

            <div class="dream-detail">
                <span class="label">📍 Place</span>
                <p class="content">{{ $dream->place }}</p>
            </div>

            @if($dream->destiny_date)
                <div class="dream-detail">
                    <span class="label">💫 Destiny Date</span>
                    <p class="content" style="font-size: 20px; color: #764ba2; font-weight: bold;">
                        {{ $dream->destiny_date->format('l, F j, Y \a\t g:i A') }}
                    </p>
                </div>
            @endif

            <div class="dream-actions">
                @if($dream->status === 'solo' && $dream->creator_id !== Auth::id())
                    <form action="{{ route('dreams.validate', $dream->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Let's Dream This Together! ✨</button>
                    </form>
                @endif

                @if($dream->status === 'shared' && !$dream->hasPendingNegotiation())
                    <a href="{{ route('dreams.plan-destiny', $dream->id) }}" class="btn btn-success">Planning 💫</a>
                @endif

                @if($dream->status === 'scheduled')
                    <a href="{{ route('dreams.request-reschedule', $dream->id) }}" class="btn btn-secondary">📅 Reschedule</a>
                    <button type="button" onclick="document.getElementById('remove-form').style.display='block'" class="btn btn-danger">❌ Remove from Bucket List</button>
                    
                    <div id="remove-form" style="display: none; margin-top: 20px; padding: 20px; background: #fff3cd; border-radius: 12px;">
                        <h4 style="color: #856404;">Remove this dream from Bucket List?</h4>
                        <p style="color: #666; margin: 10px 0;">This will move the dream back to Shared Dreams (no schedule). Your partner must confirm.</p>
                        <form action="{{ route('dreams.request-remove', $dream->id) }}" method="POST">
                            @csrf
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Reason (Required):</label>
                                <textarea name="message" required rows="3" style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 8px;" placeholder="Why do you want to remove this from the bucket list?"></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger">Send Removal Request</button>
                            <button type="button" onclick="document.getElementById('remove-form').style.display='none'" class="btn btn-secondary">Cancel</button>
                        </form>
                    </div>
                @endif

                @if($dream->status === 'cherished')
                    <button type="button" onclick="document.getElementById('fulfilled-form').style.display='block'" class="btn btn-success">✨ Mark as Fulfilled</button>
                    <button type="button" onclick="document.getElementById('missed-form').style.display='block'" class="btn btn-secondary">📅 We Missed It</button>
                    
                    <div id="fulfilled-form" style="display: none; margin-top: 20px; padding: 20px; background: #d4edda; border-radius: 12px;">
                        <h4 style="color: #155724;">🎉 Mark this dream as fulfilled?</h4>
                        <p style="color: #666; margin: 10px 0;">This will move it to "Lived in the Dream" section!</p>
                        <form action="{{ route('dreams.mark-fulfilled', $dream->id) }}" method="POST">
                            @csrf
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Share your experience (Required):</label>
                                <textarea name="message" required rows="3" style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 8px;" placeholder="How was it? What made it special?"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">✨ Yes, We Lived It!</button>
                            <button type="button" onclick="document.getElementById('fulfilled-form').style.display='none'" class="btn btn-secondary">Cancel</button>
                        </form>
                    </div>

                    <div id="missed-form" style="display: none; margin-top: 20px; padding: 20px; background: #f8d7da; border-radius: 12px;">
                        <h4 style="color: #721c24;">We missed this schedule?</h4>
                        <p style="color: #666; margin: 10px 0;">This will move it back to Shared Dreams so you can reschedule.</p>
                        <form action="{{ route('dreams.mark-missed', $dream->id) }}" method="POST">
                            @csrf
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; font-weight: bold; margin-bottom: 5px;">What happened? (Required):</label>
                                <textarea name="message" required rows="3" style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 8px;" placeholder="Why couldn't you make it?"></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger">Mark as Missed</button>
                            <button type="button" onclick="document.getElementById('missed-form').style.display='none'" class="btn btn-secondary">Cancel</button>
                        </form>
                    </div>
                @endif

                @if(in_array($dream->status, ['solo', 'shared', 'planning']) && $dream->creator_id === Auth::id())
                    <a href="{{ route('dreams.edit', $dream->id) }}" class="btn btn-secondary">Edit Dream</a>
                @endif

                @if(in_array($dream->status, ['solo', 'shared', 'planning']))
                    <form action="{{ route('dreams.destroy', $dream->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this dream?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Dream</button>
                    </form>
                @endif
            </div>
        </div>

        @if($negotiations->isNotEmpty())
            <div class="negotiations-section">
                <h3>💬 Planning History</h3>
                
                @foreach($negotiations as $negotiation)
                    <div class="negotiation-item">
                        <div class="negotiation-header">
                            <span class="proposer">
                                @if($negotiation->proposed_by === Auth::id())
                                    You
                                @else
                                    {{ $partner->name }}
                                @endif
                                @if($negotiation->status === 'rescheduled')
                                    requested reschedule:
                                @elseif($negotiation->status === 'remove_requested')
                                    requested removal:
                                @elseif($negotiation->status === 'remove_confirmed')
                                    confirmed removal:
                                @elseif($negotiation->status === 'missed')
                                    marked as missed:
                                @else
                                    proposed:
                                @endif
                            </span>
                            <span class="status-badge status-{{ $negotiation->status }}">
                                {{ ucfirst(str_replace('_', ' ', $negotiation->status)) }}
                            </span>
                        </div>
                        
                        @if($negotiation->status !== 'remove_requested' && $negotiation->status !== 'remove_confirmed')
                            <div class="negotiation-date">
                                📅 {{ $negotiation->proposed_date->format('l, F j, Y \a\t g:i A') }}
                            </div>
                        @endif
                        
                        @if($negotiation->message)
                            <div class="negotiation-message">
                                💭 "{{ $negotiation->message }}"
                            </div>
                        @endif

                        {{-- Show approve/reject buttons for pending removal requests --}}
                        @if($negotiation->status === 'remove_requested' && $negotiation->proposed_by !== Auth::id() && !$negotiation->responded_by)
                            <div style="margin-top: 15px; padding: 15px; background: #fff3cd; border-radius: 8px;">
                                <p style="color: #856404; font-weight: bold;">Your partner wants to remove this from the bucket list. Do you agree?</p>
                                <form action="{{ route('dreams.confirm-remove', [$dream->id, $negotiation->id]) }}" method="POST" style="margin-top: 10px;">
                                    @csrf
                                    <div style="margin-bottom: 10px;">
                                        <label style="display: block; margin-bottom: 5px;">Your comment (Required):</label>
                                        <textarea name="message" required rows="2" style="width: 100%; padding: 8px; border: 2px solid #e0e0e0; border-radius: 8px;" placeholder="Share your thoughts..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success" style="font-size: 14px; padding: 8px 16px;">✓ Agree to Remove</button>
                                </form>
                            </div>
                        @endif

                        {{-- Show approve/reject buttons for rescheduling --}}
                        @if($negotiation->status === 'rescheduled' && $negotiation->proposed_by !== Auth::id() && !$negotiation->responded_by && $dream->status === 'planning')
                            <div style="margin-top: 15px; padding: 15px; background: #e7f3ff; border-radius: 8px;">
                                <p style="color: #004085; font-weight: bold;">Reschedule request pending your approval</p>
                                <div style="display: flex; gap: 10px; margin-top: 10px;">
                                    <form action="{{ route('dreams.accept-date', [$dream->id, $negotiation->id]) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success" style="font-size: 14px; padding: 8px 16px;">✓ Accept New Date</button>
                                    </form>
                                    <button onclick="document.getElementById('counter-{{ $negotiation->id }}').style.display='block'" class="btn btn-secondary" style="font-size: 14px; padding: 8px 16px;">✏️ Suggest Different Time</button>
                                </div>
                                <div id="counter-{{ $negotiation->id }}" style="display: none; margin-top: 15px;">
                                    <a href="{{ route('dreams.plan-destiny', $dream->id) }}" class="btn btn-primary" style="font-size: 14px; padding: 8px 16px;">Go to Planning Page</a>
                                </div>
                            </div>
                        @endif

                        @if($negotiation->responded_by)
                            <div style="margin-top: 10px; color: #666; font-size: 14px;">
                                Responded by 
                                @if($negotiation->responded_by === Auth::id())
                                    You
                                @else
                                    {{ $partner->name }}
                                @endif
                                on {{ $negotiation->responded_at->format('M j, Y \a\t g:i A') }}
                            </div>
                        @endif

                        <div style="margin-top: 10px; color: #999; font-size: 12px;">
                            {{ $negotiation->created_at->diffForHumans() }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
