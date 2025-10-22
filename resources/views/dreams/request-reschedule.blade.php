<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reschedule - {{ $dream->heading }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body { font-family: 'Georgia', serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .card { background: white; border-radius: 20px; padding: 40px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); }
        .header h1 { color: #764ba2; font-size: 32px; margin-bottom: 10px; }
        .header p { color: #666; font-size: 16px; }
        .current-schedule { background: #fff3cd; padding: 20px; border-radius: 12px; margin: 20px 0; border-left: 4px solid #ffc107; }
        .current-schedule h3 { color: #856404; margin-bottom: 10px; }
        .current-schedule .date { font-size: 20px; font-weight: bold; color: #333; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 8px; color: #333; }
        .form-group label .required { color: #e74c3c; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 15px; font-family: 'Georgia', serif; }
        .form-group textarea { resize: vertical; min-height: 100px; }
        .form-row { display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; margin-bottom: 20px; }
        .btn { padding: 12px 24px; border-radius: 25px; font-weight: bold; border: none; cursor: pointer; transition: all 0.3s; text-decoration: none; display: inline-block; }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4); }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-secondary:hover { background: #5a6268; }
        .nav-link { display: inline-block; padding: 10px 20px; background: white; color: #764ba2; text-decoration: none; border-radius: 20px; margin-bottom: 20px; }
        .alert { padding: 15px; border-radius: 10px; margin-bottom: 20px; }
        .alert-danger { background: #f8d7da; color: #721c24; }
        
        /* AM/PM Toggle Styles */
        .time-container { display: flex; flex-direction: column; gap: 5px; }
        .ampm-toggle-container { display: flex; gap: 0; background: #e0e0e0; border-radius: 8px; overflow: hidden; height: 40px; }
        .ampm-option { flex: 1; background: transparent; border: none; color: #666; font-weight: bold; cursor: pointer; transition: all 0.3s; font-family: 'Georgia', serif; font-size: 14px; }
        .ampm-option.active { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .ampm-option:hover:not(.active) { background: #d0d0d0; }

        /* Responsive Design */
        @media (max-width: 768px) {
            body { padding: 15px; }
            .card { padding: 25px 20px; border-radius: 15px; }
            .header h1 { font-size: 26px; }
            .form-row { grid-template-columns: repeat(3, 1fr); gap: 8px; }
            .form-group input, .form-group textarea { font-size: 14px; padding: 10px; }
            .btn { padding: 10px 20px; font-size: 14px; }
            .nav-link { padding: 8px 16px; font-size: 14px; }
            .current-schedule { padding: 15px; }
            .current-schedule .date { font-size: 18px; }
        }

        @media (max-width: 480px) {
            body { padding: 10px; }
            .card { padding: 20px 15px; }
            .header h1 { font-size: 22px; }
            .header p { font-size: 14px; }
            .form-row { grid-template-columns: repeat(2, 1fr); gap: 6px; }
            .form-group input, .form-group textarea { font-size: 13px; padding: 9px; }
            .btn { padding: 9px 18px; font-size: 13px; }
            .current-schedule .date { font-size: 16px; }
        }
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

        function toggleAMPM(containerId, period) {
            const container = document.getElementById(containerId);
            const buttons = container.querySelectorAll('.ampm-option');
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
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

        document.addEventListener('DOMContentLoaded', function() {
            setupTimeConversion('reschedule-form', 'reschedule-hour12', 'reschedule-hour24', 'reschedule-toggle');
        });
    </script>
</head>
<body>
    <div class="container">
        <a href="{{ route('dreams.show', $dream->id) }}" class="nav-link">← Back to Dream Details</a>
        
        <div class="card">
            <div class="header">
                <h1>📅 Reschedule Request</h1>
                <p>{{ $dream->heading }}</p>
            </div>

            <div class="current-schedule">
                <h3>Current Schedule:</h3>
                <div class="date">{{ $dream->destiny_date->format('l, F j, Y \a\t g:i A') }}</div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="reschedule-form" action="{{ route('dreams.submit-reschedule', $dream->id) }}" method="POST">
                @csrf
                <h3 style="color: #764ba2; margin-bottom: 20px;">Propose New Date & Time</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Year <span class="required">*</span></label>
                        <input type="number" name="year" min="{{ date('Y') }}" value="{{ $dream->destiny_date->year }}" required>
                    </div>
                    <div class="form-group">
                        <label>Month <span class="required">*</span></label>
                        <input type="number" name="month" min="1" max="12" value="{{ $dream->destiny_date->month }}" required>
                    </div>
                    <div class="form-group">
                        <label>Day <span class="required">*</span></label>
                        <input type="number" name="day" min="1" max="31" value="{{ $dream->destiny_date->day }}" required>
                    </div>
                    <div class="form-group">
                        <label>Hour <span class="required">*</span></label>
                        <div class="time-container">
                            @php
                                $hour24 = $dream->destiny_date->hour;
                                $hour12 = $hour24 === 0 ? 12 : ($hour24 <= 12 ? $hour24 : $hour24 - 12);
                                $period = $hour24 < 12 ? 'AM' : 'PM';
                                if ($hour24 === 0) $period = 'AM';
                            @endphp
                            <input type="number" id="reschedule-hour12" min="1" max="12" value="{{ $hour12 }}" required>
                            <input type="hidden" name="hour" id="reschedule-hour24" value="{{ $hour24 }}">
                            <div class="ampm-toggle-container" id="reschedule-toggle" data-period="{{ $period }}">
                                <button type="button" class="ampm-option {{ $period === 'AM' ? 'active' : '' }}" onclick="toggleAMPM('reschedule-toggle', 'AM')">AM</button>
                                <button type="button" class="ampm-option {{ $period === 'PM' ? 'active' : '' }}" onclick="toggleAMPM('reschedule-toggle', 'PM')">PM</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Minute <span class="required">*</span></label>
                        <input type="number" name="minute" min="0" max="59" value="{{ $dream->destiny_date->minute }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Reason for Rescheduling <span class="required">*</span></label>
                    <textarea name="message" required placeholder="Please explain why you need to reschedule... (This is required)"></textarea>
                    <small style="color: #666;">Your partner will see this message when reviewing your request.</small>
                </div>

                <div style="display: flex; gap: 15px; margin-top: 30px;">
                    <button type="submit" class="btn btn-primary">📅 Send Reschedule Request</button>
                    <a href="{{ route('dreams.show', $dream->id) }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
