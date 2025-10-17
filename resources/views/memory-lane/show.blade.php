<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $memory->heading }} - AmoreCanvas</title>
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
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar h1 {
            color: #e91e63;
            font-size: 24px;
            max-width: 500px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .navbar-right {
            display: flex;
            gap: 10px;
        }

        .back-btn, .edit-btn, .delete-btn {
            padding: 10px 20px;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .back-btn {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .edit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .delete-btn {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .back-btn:hover, .edit-btn:hover, .delete-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(245, 87, 108, 0.3);
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 40px 60px;
        }

        .memory-details {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(255, 105, 135, 0.3);
            overflow: hidden;
            margin-bottom: 40px;
        }

        .memory-media-container {
            width: 100%;
            max-height: 500px;
            background: #000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .memory-media-container img {
            width: 100%;
            height: auto;
            max-height: 500px;
            object-fit: contain;
        }

        .memory-media-container video {
            width: 100%;
            max-height: 500px;
        }

        .memory-media-container audio {
            width: 100%;
            padding: 20px;
        }

        .memory-media-placeholder {
            padding: 80px;
            text-align: center;
            font-size: 120px;
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        }

        .memory-content {
            padding: 40px;
        }

        .memory-date {
            font-size: 14px;
            color: #ff6b9d;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .memory-heading {
            font-size: 36px;
            color: #e91e63;
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .memory-title {
            font-size: 20px;
            color: #888;
            margin-bottom: 20px;
            font-style: italic;
        }

        .memory-description {
            font-size: 17px;
            color: #555;
            line-height: 1.8;
            margin-bottom: 25px;
            white-space: pre-line;
        }

        .memory-author {
            font-size: 15px;
            color: #999;
            font-style: italic;
            padding-top: 20px;
            border-top: 2px solid rgba(255, 182, 193, 0.3);
        }

        /* Reviews Section */
        .reviews-section {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(255, 105, 135, 0.3);
            padding: 40px;
        }

        .reviews-section h3 {
            color: #e91e63;
            font-size: 28px;
            margin-bottom: 30px;
        }

        .add-review-form {
            background: rgba(255, 250, 250, 0.8);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            border: 2px solid rgba(255, 182, 193, 0.3);
        }

        .add-review-form textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid rgba(255, 182, 193, 0.5);
            border-radius: 10px;
            font-size: 15px;
            font-family: 'Georgia', serif;
            min-height: 100px;
            resize: vertical;
            margin-bottom: 15px;
        }

        .add-review-form textarea:focus {
            outline: none;
            border-color: #ff6b9d;
            box-shadow: 0 0 10px rgba(255, 107, 157, 0.2);
        }

        .review-form-footer {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .file-upload-label {
            flex: 1;
            padding: 10px 15px;
            background: rgba(255, 107, 157, 0.1);
            border: 2px dashed rgba(255, 182, 193, 0.5);
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            font-size: 14px;
            color: #666;
            transition: all 0.3s ease;
        }

        .file-upload-label:hover {
            border-color: #ff6b9d;
            background: rgba(255, 107, 157, 0.2);
        }

        .file-upload-label input[type="file"] {
            opacity: 0;
            position: absolute;
            pointer-events: none;
        }
        
        /* Make file input visible and styled */
        .review-form-footer input[type="file"] {
            opacity: 1 !important;
            position: relative !important;
            pointer-events: all !important;
            display: block !important;
            width: 100%;
            padding: 10px;
            border: 2px dashed rgba(255, 182, 193, 0.5);
            border-radius: 8px;
            background: rgba(255, 107, 157, 0.1);
            cursor: pointer;
        }

        .submit-review-btn {
            padding: 12px 30px;
            background: linear-gradient(135deg, #ff6b9d 0%, #e91e63 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .submit-review-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(233, 30, 99, 0.4);
        }

        .review-item {
            background: rgba(255, 250, 250, 0.6);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 15px;
            border-left: 4px solid #ff6b9d;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .reviewer-name {
            font-size: 16px;
            color: #e91e63;
            font-weight: bold;
        }

        .review-date {
            font-size: 13px;
            color: #999;
        }

        .review-text {
            font-size: 15px;
            color: #555;
            line-height: 1.7;
            margin-bottom: 10px;
            white-space: pre-line;
        }

        .review-media {
            margin-top: 15px;
            max-width: 100%;
        }

        .review-media img {
            max-width: 100%;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .review-media audio,
        .review-media video {
            max-width: 100%;
            border-radius: 10px;
        }

        .review-actions {
            display: flex;
            gap: 10px;
            margin-top: 12px;
        }

        .edit-review-btn, .delete-review-btn {
            padding: 6px 15px;
            font-size: 13px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .edit-review-btn {
            background: #667eea;
            color: white;
        }

        .delete-review-btn {
            background: #dc3545;
            color: white;
        }

        .edit-review-btn:hover, .delete-review-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }

        .no-reviews {
            text-align: center;
            padding: 40px;
            color: #999;
            font-size: 16px;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 15px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        /* Edit review form (hidden by default) */
        .edit-review-form {
            display: none;
            margin-top: 15px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            border: 2px solid #ff6b9d;
        }

        .edit-review-form.active {
            display: block;
        }

        .edit-review-form textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid rgba(255, 182, 193, 0.5);
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Georgia', serif;
            min-height: 80px;
            margin-bottom: 10px;
        }

        .edit-review-form button {
            margin-right: 10px;
        }

        .cancel-edit-btn {
            padding: 8px 20px;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>{{ $memory->heading }}</h1>
        <div class="navbar-right">
            <a href="{{ route('memory-lane.index') }}" class="back-btn">← Back</a>
            @if(Auth::id() === $memory->user_id)
                <a href="{{ route('memory-lane.edit', $memory->id) }}" class="edit-btn">✏️ Edit</a>
                <form method="POST" action="{{ route('memory-lane.destroy', $memory->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this memory?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-btn">🗑️ Delete</button>
                </form>
            @endif
        </div>
    </div>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="memory-details">
            @if($memory->love_letter_id && $memory->letter_content)
                <!-- Love Letter Content Display -->
                <div class="memory-media-container" style="background: linear-gradient(135deg, #fff5f8 0%, #ffe6f0 100%); border: 2px solid #e91e63; padding: 30px;">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <span style="font-size: 48px;">💌</span>
                        <p style="color: #e91e63; font-size: 14px; margin-top: 10px;">Love Letter Preserved in Memory Lane</p>
                    </div>
                    <div class="love-letter-content" style="font-family: 'Georgia', serif; line-height: 1.8; color: #333;">
                        {!! $memory->letter_content !!}
                    </div>
                </div>
            @elseif($memory->media_path)
                <div class="memory-media-container">
                    @if($memory->media_type === 'image')
                        <img src="{{ asset('storage/' . $memory->media_path) }}" alt="{{ $memory->heading }}">
                    @elseif($memory->media_type === 'video')
                        <video controls>
                            <source src="{{ asset('storage/' . $memory->media_path) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @elseif($memory->media_type === 'audio')
                        <audio controls>
                            <source src="{{ asset('storage/' . $memory->media_path) }}" type="audio/mpeg">
                            Your browser does not support the audio tag.
                        </audio>
                    @endif
                </div>
            @else
                <div class="memory-media-placeholder">
                    @if($memory->media_type === 'image')
                        📸
                    @elseif($memory->media_type === 'video')
                        🎥
                    @elseif($memory->media_type === 'audio')
                        🎵
                    @else
                        📝
                    @endif
                </div>
            @endif

            <div class="memory-content">
                <div class="memory-date">{{ $memory->story_date->format('F j, Y') }}</div>
                <h2 class="memory-heading">{{ $memory->heading }}</h2>
                @if($memory->title)
                    <div class="memory-title">{{ $memory->title }}</div>
                @endif
                <div class="memory-description">{{ $memory->description }}</div>
                <div class="memory-author">Shared by {{ $memory->user->name }} 💕</div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="reviews-section">
            <h3>💬 Reviews</h3>

            <!-- Reviews List -->
            @if($memory->reviews->count() > 0)
                @foreach($memory->reviews as $review)
                    <div class="review-item" id="review-{{ $review->id }}">
                        <div class="review-header">
                            <span class="reviewer-name">{{ $review->reviewer->name }}</span>
                            <span class="review-date">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <div class="review-content-{{ $review->id }}">
                            <div class="review-text">{{ $review->review }}</div>
                            
                            @if($review->media_path)
                                <div class="review-media">
                                    @php
                                        $ext = strtolower(pathinfo($review->media_path, PATHINFO_EXTENSION));
                                        $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
                                        $videoExts = ['mp4', 'webm', 'ogg', 'avi', 'mov'];
                                        $audioExts = ['mp3', 'wav', 'ogg', 'aac', 'm4a'];
                                    @endphp
                                    
                                    @if(in_array($ext, $imageExts))
                                        <img src="{{ asset('storage/' . $review->media_path) }}" alt="Review media" style="max-width: 100%; border-radius: 8px; margin-top: 10px;">
                                    @elseif(in_array($ext, $videoExts))
                                        <video controls style="max-width: 100%; border-radius: 8px; margin-top: 10px;">
                                            <source src="{{ asset('storage/' . $review->media_path) }}">
                                            Your browser does not support the video tag.
                                        </video>
                                    @elseif(in_array($ext, $audioExts))
                                        <audio controls style="width: 100%; margin-top: 10px;">
                                            <source src="{{ asset('storage/' . $review->media_path) }}">
                                            Your browser does not support the audio tag.
                                        </audio>
                                    @else
                                        <a href="{{ asset('storage/' . $review->media_path) }}" target="_blank" style="color: #e91e63; text-decoration: underline;">📎 View Attached File</a>
                                    @endif
                                </div>
                            @endif
                        </div>

                        @if(Auth::id() === $review->reviewer_id)
                            <div class="review-actions">
                                <button class="edit-review-btn" onclick="showEditForm({{ $review->id }})">✏️ Edit</button>
                                <form method="POST" action="{{ route('memory-lane.review.destroy', $review->id) }}" style="display: inline;" onsubmit="return confirm('Delete this review?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-review-btn">🗑️ Delete</button>
                                </form>
                            </div>

                            <!-- Edit Form (Hidden by default) -->
                            <div class="edit-review-form" id="edit-form-{{ $review->id }}">
                                <form method="POST" action="{{ route('memory-lane.review.update', $review->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <textarea name="review" required>{{ $review->review }}</textarea>
                                    <label class="file-upload-label">
                                        📎 Change Media (Optional)
                                        <input type="file" name="media_file" accept="image/*,video/*,audio/*">
                                    </label>
                                    <button type="submit" class="submit-review-btn">💕 Update</button>
                                    <button type="button" class="cancel-edit-btn" onclick="hideEditForm({{ $review->id }})">Cancel</button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="no-reviews">
                    No reviews yet. Be the first to share your thoughts! 💕
                </div>
            @endif

            <!-- Add Review Form (at the bottom) -->
            <div class="add-review-form">
                @if (session('review_success'))
                    <div style="padding: 15px; background: #d4edda; color: #155724; border-radius: 8px; margin-bottom: 15px;">
                        {{ session('review_success') }}
                    </div>
                @endif
                
                @if (session('review_error'))
                    <div style="padding: 15px; background: #f8d7da; color: #721c24; border-radius: 8px; margin-bottom: 15px;">
                        {{ session('review_error') }}
                    </div>
                @endif
                
                <form method="POST" action="{{ route('memory-lane.review.store', $memory->id) }}" enctype="multipart/form-data">
                    @csrf
                    <textarea name="review" placeholder="Share your thoughts about this memory..." required>{{ old('review') }}</textarea>
                    
                    <div class="review-form-footer">
                        <div style="flex: 1;">
                            <label for="review-media-input" style="display: block; font-size: 14px; color: #666; margin-bottom: 5px;">
                                📎 Attach Media (Optional)
                            </label>
                            <input type="file" name="media_file" accept="image/*,video/*,audio/*" id="review-media-input">
                        </div>
                        <button type="submit" class="submit-review-btn">💕 Add Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showEditForm(reviewId) {
            document.getElementById('edit-form-' + reviewId).classList.add('active');
            document.querySelector('.review-content-' + reviewId).style.display = 'none';
        }

        function hideEditForm(reviewId) {
            document.getElementById('edit-form-' + reviewId).classList.remove('active');
            document.querySelector('.review-content-' + reviewId).style.display = 'block';
        }

        // File upload label text update
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                if (this.files.length > 0) {
                    // Find the label for this input
                    const label = document.querySelector('label[for="' + this.id + '"]');
                    if (label) {
                        label.textContent = '📎 ' + this.files[0].name;
                    }
                    console.log('File selected:', this.files[0].name, 'Size:', this.files[0].size, 'Type:', this.files[0].type);
                } else {
                    console.log('No file selected');
                }
            });
        });

        // Debug: Log form data before submission
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const formData = new FormData(this);
                console.log('=== FORM SUBMISSION DEBUG ===');
                console.log('Form action:', this.action);
                console.log('Form method:', this.method);
                console.log('Form enctype:', this.enctype);
                
                for (let [key, value] of formData.entries()) {
                    if (value instanceof File) {
                        console.log(key + ':', value.name, '(' + value.size + ' bytes)');
                    } else {
                        console.log(key + ':', value);
                    }
                }
                console.log('=== END DEBUG ===');
            });
        });
    </script>
</body>
</html>
