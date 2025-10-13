<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Memory - AmoreCanvas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 50%, #ff9a9e 100%);
            min-height: 100vh;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 {
            color: #e91e63;
            font-size: 28px;
        }

        .back-btn {
            padding: 10px 25px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(245, 87, 108, 0.3);
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 40px;
        }

        .form-card {
            background: rgba(255, 255, 255, 0.98);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(255, 105, 135, 0.3);
        }

        .form-card h2 {
            color: #e91e63;
            font-size: 36px;
            margin-bottom: 10px;
            text-align: center;
        }

        .form-card p {
            color: #666;
            text-align: center;
            margin-bottom: 30px;
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            color: #e91e63;
            font-size: 16px;
            margin-bottom: 8px;
            font-weight: bold;
        }

        label .optional {
            color: #999;
            font-weight: normal;
            font-size: 14px;
        }

        input[type="text"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid rgba(255, 182, 193, 0.5);
            border-radius: 10px;
            font-size: 15px;
            font-family: 'Georgia', serif;
            transition: all 0.3s ease;
            background: white;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #ff6b9d;
            box-shadow: 0 0 10px rgba(255, 107, 157, 0.2);
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 2px dashed rgba(255, 182, 193, 0.5);
            border-radius: 10px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        input[type="file"]:hover {
            border-color: #ff6b9d;
            background: rgba(255, 107, 157, 0.05);
        }

        .file-info {
            margin-top: 8px;
            font-size: 13px;
            color: #888;
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #ff6b9d 0%, #e91e63 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Georgia', serif;
            margin-top: 10px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(233, 30, 99, 0.4);
        }

        .error-message {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
        }

        .media-type-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-top: 10px;
        }

        .media-type-option {
            padding: 15px;
            border: 2px solid rgba(255, 182, 193, 0.5);
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }

        .media-type-option input[type="radio"] {
            display: none;
        }

        .media-type-option:hover {
            border-color: #ff6b9d;
            background: rgba(255, 107, 157, 0.05);
        }

        .media-type-option.selected {
            border-color: #e91e63;
            background: rgba(255, 107, 157, 0.1);
        }

        .media-type-icon {
            font-size: 32px;
            margin-bottom: 5px;
            transition: transform 0.3s ease;
        }

        .media-type-label {
            font-size: 14px;
            color: #666;
        }

        .media-type-option.selected .media-type-label {
            color: #e91e63;
            font-weight: bold;
        }

        .current-media {
            margin-top: 10px;
            padding: 10px;
            background: rgba(255, 107, 157, 0.05);
            border-radius: 8px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>✏️ Edit Memory</h1>
        <a href="{{ route('memory-lane.show', $memory->id) }}" class="back-btn">← Cancel</a>
    </div>

    <div class="container">
        <div class="form-card">
            <h2>Edit Your Memory</h2>
            <p>Update your precious moment 💕</p>

            <form method="POST" action="{{ route('memory-lane.update', $memory->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="heading">Heading *</label>
                    <input type="text" id="heading" name="heading" value="{{ old('heading', $memory->heading) }}" required 
                           placeholder="e.g., Our First Date">
                    @error('heading')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="title">Title <span class="optional">(Optional)</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title', $memory->title) }}"
                           placeholder="e.g., A Magical Evening">
                    @error('title')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="story_date">When did this happen? *</label>
                    <input type="date" id="story_date" name="story_date" value="{{ old('story_date', $memory->story_date->format('Y-m-d')) }}" required>
                    @error('story_date')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Tell your story *</label>
                    <textarea id="description" name="description" required 
                              placeholder="Describe this beautiful memory...">{{ old('description', $memory->description) }}</textarea>
                    @error('description')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Media Type *</label>
                    <div class="media-type-grid">
                        <div class="media-type-option {{ old('media_type', $memory->media_type) == 'image' ? 'selected' : '' }}" onclick="selectMediaType('image', this)">
                            <div class="media-type-icon">📸</div>
                            <div class="media-type-label">Image</div>
                            <input type="radio" name="media_type" value="image" {{ old('media_type', $memory->media_type) == 'image' ? 'checked' : '' }} required>
                        </div>
                        <div class="media-type-option {{ old('media_type', $memory->media_type) == 'video' ? 'selected' : '' }}" onclick="selectMediaType('video', this)">
                            <div class="media-type-icon">🎥</div>
                            <div class="media-type-label">Video</div>
                            <input type="radio" name="media_type" value="video" {{ old('media_type', $memory->media_type) == 'video' ? 'checked' : '' }} required>
                        </div>
                        <div class="media-type-option {{ old('media_type', $memory->media_type) == 'audio' ? 'selected' : '' }}" onclick="selectMediaType('audio', this)">
                            <div class="media-type-icon">🎵</div>
                            <div class="media-type-label">Audio</div>
                            <input type="radio" name="media_type" value="audio" {{ old('media_type', $memory->media_type) == 'audio' ? 'checked' : '' }} required>
                        </div>
                        <div class="media-type-option {{ old('media_type', $memory->media_type) == 'text' ? 'selected' : '' }}" onclick="selectMediaType('text', this)">
                            <div class="media-type-icon">📝</div>
                            <div class="media-type-label">Text Only</div>
                            <input type="radio" name="media_type" value="text" {{ old('media_type', $memory->media_type) == 'text' ? 'checked' : '' }} required>
                        </div>
                    </div>
                    @error('media_type')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="media_file">Upload Media <span class="optional">(Optional, Max 50MB)</span></label>
                    @if($memory->media_path)
                        <div class="current-media">
                            Current media: {{ basename($memory->media_path) }}
                        </div>
                    @endif
                    <input type="file" id="media_file" name="media_file" accept="image/*,video/*,audio/*">
                    <div class="file-info">Upload a new file to replace the current one. Supported: Images, Videos, Audio files</div>
                    @error('media_file')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="submit-btn">💕 Update Memory</button>
            </form>
        </div>
    </div>

    <script>
        function selectMediaType(type, element) {
            // Remove selected class from all options
            document.querySelectorAll('.media-type-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            element.classList.add('selected');
            
            // Check the radio button
            element.querySelector('input[type="radio"]').checked = true;
        }
    </script>
</body>
</html>
