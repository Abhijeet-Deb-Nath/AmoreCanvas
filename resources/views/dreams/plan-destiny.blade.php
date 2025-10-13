<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning - {{ $dream->heading }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body { font-family: 'Georgia', serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; }
        .card { background: white; border-radius: 20px; padding: 40px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); }
        .header h1 { color: #764ba2; font-size: 32px; margin-bottom: 20px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 8px; color: #333; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 15px; }
        .form-row { display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; }
        .btn { padding: 12px 24px; border-radius: 25px; font-weight: bold; border: none; cursor: pointer; transition: all 0.3s; }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .btn-success { background: #66bb6a; color: white; }
        .btn-danger { background: #ef5350; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .negotiations { margin-top: 40px; }
        .negotiation-item { background: #f8f9fa; border-left: 4px solid #764ba2; padding: 20px; margin-bottom: 15px; border-radius: 10px; }
        .status-badge { padding: 5px 12px; border-radius: 15px; font-size: 12px; font-weight: bold; }
        .status-pending { background: #ffa726; color: white; }
        .status-accepted { background: #66bb6a; color: white; }
        .status-rejected { background: #ef5350; color: white; }
        .nav-link { display: inline-block; padding: 10px 20px; background: white; color: #764ba2; text-decoration: none; border-radius: 20px; margin-bottom: 20px; }
        
        /* AM/PM Toggle Styles */
        .time-container { display: flex; flex-direction: column; gap: 5px; }
        .ampm-toggle-container { display: flex; gap: 0; background: #e0e0e0; border-radius: 8px; overflow: hidden; height: 40px; }
        .ampm-option { flex: 1; background: transparent; border: none; color: #666; font-weight: bold; cursor: pointer; transition: all 0.3s; font-family: 'Georgia', serif; font-size: 14px; }
        .ampm-option.active { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .ampm-option:hover:not(.active) { background: #d0d0d0; }
    </style>
    <script>
        function convert12to24(hour12, period) {
            hour12 = parseInt(hour12);
            if (period === 'AM') {
                if (hour12 === 12) return 0;
                return hour12;
            } else {
                if (hour12 === 12) return 12;
                return hour12 + 12;
            }
        }

        function convert24to12(hour24) {
            hour24 = parseInt(hour24);
            if (hour24 === 0) return { hour: 12, period: 'AM' };
            if (hour24 < 12) return { hour: hour24, period: 'AM' };
            if (hour24 === 12) return { hour: 12, period: 'PM' };
            return { hour: hour24 - 12, period: 'PM' };
        }

        function toggleAMPM(containerId, period) {
            const container = document.getElementById(containerId);
            const buttons = container.querySelectorAll('.ampm-option');
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            // Store the selected period
            container.dataset.period = period;
        }

        function setupTimeConversion(formId, hour12InputId, hourHiddenId, toggleId) {
            const form = document.getElementById(formId);
            form.addEventListener('submit', function(e) {
                const hour12Input = document.getElementById(hour12InputId);
                const hourHidden = document.getElementById(hourHiddenId);
                const toggle = document.getElementById(toggleId);
                const period = toggle.dataset.period || 'PM';
                const hour24 = convert12to24(hour12Input.value, period);
                hourHidden.value = hour24;
            });
        }
    </script>
</head>
<body>
    @php
        function convert24to12($hour24) {
            $hour24 = intval($hour24);
            if ($hour24 === 0) return ['hour' => 12, 'period' => 'AM'];
            if ($hour24 < 12) return ['hour' => $hour24, 'period' => 'AM'];
            if ($hour24 === 12) return ['hour' => 12, 'period' => 'PM'];
            return ['hour' => $hour24 - 12, 'period' => 'PM'];
        }
    @endphp
    <div class="container">
        <a href="{{ route('dreams.show', $dream->id) }}" class="nav-link">← Back to Dream</a>
        
        <div class="card">
            <div class="header">
                <h1>💫 Planning: {{ $dream->heading }}</h1>
                <p style="color: #666;">Coordinate the perfect moment with {{ $partner->name }}</p>
            </div>

            @if(session('success'))
                <div style="padding: 15px; background: #d4edda; color: #155724; border-radius: 10px; margin-bottom: 20px;">{{ session('success') }}</div>
            @endif

            @if($pendingNegotiation && $pendingNegotiation->proposed_by !== Auth::id())
                <div style="background: #fff3cd; padding: 20px; border-radius: 12px; margin-bottom: 25px;">
                    <h3 style="color: #856404; margin-bottom: 15px;">{{ $partner->name }} proposed a date:</h3>
                    <p style="font-size: 20px; color: #333; font-weight: bold;">{{ $pendingNegotiation->proposed_date->format('l, F j, Y \a\t g:i A') }}</p>
                    @if($pendingNegotiation->message)
                        <p style="font-style: italic; color: #666; margin: 10px 0;">💭 "{{ $pendingNegotiation->message }}"</p>
                    @endif
                    
                    <div style="display: flex; gap: 15px; margin-top: 20px;">
                        <form action="{{ route('dreams.accept-date', [$dream->id, $pendingNegotiation->id]) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success">✓ Accept This Date</button>
                        </form>
                        <form action="{{ route('dreams.reject-date', [$dream->id, $pendingNegotiation->id]) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger">✗ Decline</button>
                        </form>
                        <button onclick="document.getElementById('edit-form').style.display='block'" class="btn btn-secondary">✏️ Suggest Different Time</button>
                    </div>

                    <form id="edit-form" action="{{ route('dreams.edit-date', [$dream->id, $pendingNegotiation->id]) }}" method="POST" style="display: none; margin-top: 25px; padding-top: 25px; border-top: 2px dashed #ddd;">
                        @csrf
                        <h4 style="color: #764ba2; margin-bottom: 15px;">Suggest a Different Time:</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Year</label>
                                <input type="number" name="year" min="{{ date('Y') }}" value="{{ $pendingNegotiation->proposed_date->year }}" required>
                            </div>
                            <div class="form-group">
                                <label>Month</label>
                                <input type="number" name="month" min="1" max="12" value="{{ $pendingNegotiation->proposed_date->month }}" required>
                            </div>
                            <div class="form-group">
                                <label>Day</label>
                                <input type="number" name="day" min="1" max="31" value="{{ $pendingNegotiation->proposed_date->day }}" required>
                            </div>
                            <div class="form-group">
                                <label>Hour</label>
                                <div class="time-container">
                                    @php
                                        $time12 = convert24to12($pendingNegotiation->proposed_date->hour);
                                    @endphp
                                    <input type="number" id="edit-hour12" min="1" max="12" value="{{ $time12['hour'] }}" required>
                                    <input type="hidden" name="hour" id="edit-hour24" value="{{ $pendingNegotiation->proposed_date->hour }}">
                                    <div class="ampm-toggle-container" id="edit-toggle" data-period="{{ $time12['period'] }}">
                                        <button type="button" class="ampm-option {{ $time12['period'] === 'AM' ? 'active' : '' }}" onclick="toggleAMPM('edit-toggle', 'AM')">AM</button>
                                        <button type="button" class="ampm-option {{ $time12['period'] === 'PM' ? 'active' : '' }}" onclick="toggleAMPM('edit-toggle', 'PM')">PM</button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Minute</label>
                                <input type="number" name="minute" min="0" max="59" value="{{ $pendingNegotiation->proposed_date->minute }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Your Message (optional)</label>
                            <textarea name="message" rows="3" placeholder="Share why this time works better..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Counter-Proposal</button>
                    </form>
                    <script>
                        setupTimeConversion('edit-form', 'edit-hour12', 'edit-hour24', 'edit-toggle');
                    </script>
                </div>
            @elseif($pendingNegotiation)
                <div style="background: #e7f3ff; padding: 20px; border-radius: 12px; margin-bottom: 25px;">
                    <p style="color: #004085; font-weight: bold;">⏳ Waiting for {{ $partner->name }}'s response...</p>
                    <p style="color: #666; margin-top: 10px;">You proposed: {{ $pendingNegotiation->proposed_date->format('l, F j, Y \a\t g:i A') }}</p>
                </div>
            @else
                <form id="propose-form" action="{{ route('dreams.propose-date', $dream->id) }}" method="POST">
                    @csrf
                    <h3 style="color: #764ba2; margin-bottom: 20px;">📅 Propose a Destiny Date</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Year *</label>
                            <input type="number" name="year" min="{{ date('Y') }}" value="{{ date('Y') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Month *</label>
                            <input type="number" name="month" min="1" max="12" value="{{ date('m') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Day *</label>
                            <input type="number" name="day" min="1" max="31" value="{{ date('d') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Hour *</label>
                            <div class="time-container">
                                <input type="number" id="propose-hour12" min="1" max="12" value="12" required>
                                <input type="hidden" name="hour" id="propose-hour24" value="12">
                                <div class="ampm-toggle-container" id="propose-toggle" data-period="PM">
                                    <button type="button" class="ampm-option" onclick="toggleAMPM('propose-toggle', 'AM')">AM</button>
                                    <button type="button" class="ampm-option active" onclick="toggleAMPM('propose-toggle', 'PM')">PM</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Minute *</label>
                            <input type="number" name="minute" min="0" max="59" value="0" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Your Message (optional)</label>
                        <textarea name="message" rows="3" placeholder="Share your thoughts about this date..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Propose This Date 💫</button>
                </form>
                <script>
                    setupTimeConversion('propose-form', 'propose-hour12', 'propose-hour24', 'propose-toggle');
                </script>
            @endif
        </div>

        @if($negotiations->isNotEmpty())
            <div class="card negotiations">
                <h3 style="color: #764ba2; margin-bottom: 25px;">💬 Planning History</h3>
                @foreach($negotiations as $negotiation)
                    <div class="negotiation-item">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span style="font-weight: bold; color: #764ba2;">
                                @if($negotiation->proposed_by === Auth::id()) You @else {{ $partner->name }} @endif proposed:
                            </span>
                            <span class="status-badge status-{{ $negotiation->status }}">{{ ucfirst($negotiation->status) }}</span>
                        </div>
                        <p style="font-size: 18px; color: #333; margin: 10px 0;">📅 {{ $negotiation->proposed_date->format('l, F j, Y \a\t g:i A') }}</p>
                        @if($negotiation->message)
                            <p style="font-style: italic; color: #666;">💭 "{{ $negotiation->message }}"</p>
                        @endif
                        <p style="color: #999; font-size: 12px; margin-top: 10px;">{{ $negotiation->created_at->diffForHumans() }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
