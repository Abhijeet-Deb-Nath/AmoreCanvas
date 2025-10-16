<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Memory Lane Entry - AmoreCanvas</title>
    <style>
        body { font-family: 'Georgia', serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 40px 20px; }
        .container { max-width: 700px; margin: 0 auto; background: white; border-radius: 20px; padding: 40px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); }
        .header { text-align: center; margin-bottom: 35px; }
        .header h1 { color: #764ba2; font-size: 36px; margin-bottom: 10px; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 8px; }
        .alert-error { background: #fee; color: #c00; border: 1px solid #fcc; }
        .alert-success { background: #efe; color: #070; border: 1px solid #cfc; }
        .dream-summary { background: #f8f9fa; padding: 20px; border-radius: 12px; margin-bottom: 30px; border-left: 4px solid #764ba2; }
        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; color: #333; font-weight: bold; margin-bottom: 8px; font-size: 16px; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 15px; font-family: 'Georgia', serif; }
        .form-group textarea { min-height: 100px; resize: vertical; }
        .buttons { display: flex; gap: 15px; margin-top: 35px; }
        .btn { flex: 1; padding: 15px; border: none; border-radius: 25px; font-size: 16px; font-weight: bold; cursor: pointer; text-decoration: none; text-align: center; }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .help-text { color: #666; font-size: 14px; margin-top: 5px; font-style: italic; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💫 Create Memory Lane Entry</h1>
            <p>Transform your fulfilled dream into a lasting memory</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="dream-summary">
            <h3 style="color: #764ba2; margin-bottom: 15px;">From Dream: {{ $dream->heading }}</h3>
            <p style="color: #666; margin-bottom: 10px;"><strong>Place:</strong> {{ $dream->place }}</p>
            <p style="color: #666;"><strong>Original Date:</strong> {{ $dream->destiny_date->format('F j, Y') }}</p>
        </div>

        <form action="{{ route('memory-lane.store-from-dream', $dream->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>When did you actually live this dream? <span style="color: #999; font-weight: normal;">(optional)</span></label>
                <input type="date" name="actual_date" value="{{ $dream->destiny_date->format('Y-m-d') }}">
                <div class="help-text">If different from the original destiny date</div>
            </div>

            <div class="form-group">
                <label>Extended Description <span style="color: #999; font-weight: normal;">(optional)</span></label>
                <textarea name="extended_description" placeholder="Add more details about how you lived this dream...">{{ $dream->description }}</textarea>
                <div class="help-text">Enhance the original description with your actual experience</div>
            </div>

            <div class="form-group">
                <label>Photos/Images <span style="color: #999; font-weight: normal;">(optional)</span></label>
                <input type="file" name="photos[]" multiple accept="image/*">
                <div class="help-text">Upload photos from your experience</div>
            </div>

            <div class="form-group">
                <label>Special Notes <span style="color: #999; font-weight: normal;">(optional)</span></label>
                <textarea name="special_notes" placeholder="Any special moments or feelings you want to remember..."></textarea>
            </div>

            <div class="buttons">
                <a href="{{ route('dreams.lived') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Memory 💖</button>
            </div>
        </form>
    </div>
</body>
</html>
