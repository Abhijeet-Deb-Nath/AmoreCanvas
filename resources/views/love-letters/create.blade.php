<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write a Love Letter - AmoreCanvas</title>
    
    <!-- Quill.js CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #ffeaa7 0%, #fd79a8 50%, #fdcb6e 100%);
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
            font-size: 28px;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .back-btn {
            padding: 10px 25px;
            background: linear-gradient(135deg, #e91e63 0%, #c2185b 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }

        .letter-form-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .form-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .form-header h2 {
            font-size: 32px;
            color: #e91e63;
            margin-bottom: 10px;
        }

        .form-header p {
            color: #666;
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-size: 16px;
            color: #333;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="date"] {
            width: 100%;
            padding: 12px 15px;
            font-size: 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-family: 'Georgia', serif;
            transition: all 0.3s ease;
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="date"]:focus {
            outline: none;
            border-color: #e91e63;
        }

        .form-group .help-text {
            font-size: 13px;
            color: #999;
            margin-top: 5px;
        }

        /* Quill Editor Styling */
        #editor-container {
            height: 400px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            background: #fff;
        }

        .ql-toolbar {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            background: #f9f9f9;
            border-bottom: 2px solid #e0e0e0 !important;
        }

        .ql-container {
            font-family: 'Georgia', serif;
            font-size: 16px;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .ql-editor {
            min-height: 350px;
            line-height: 1.8;
        }

        .ql-editor.ql-blank::before {
            color: #999;
            font-style: italic;
        }

        .submit-section {
            margin-top: 30px;
            text-align: center;
        }

        .submit-btn {
            padding: 15px 50px;
            background: linear-gradient(135deg, #e91e63 0%, #c2185b 100%);
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(233, 30, 99, 0.4);
        }

        .romantic-quote {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background: linear-gradient(135deg, #fff5f8 0%, #ffe6f0 100%);
            border-radius: 15px;
            border-left: 4px solid #e91e63;
        }

        .romantic-quote p {
            font-style: italic;
            color: #666;
            font-size: 16px;
        }

        .error-message {
            background: #f44336;
            color: white;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .letter-form-card {
                padding: 25px 20px;
            }

            .navbar {
                padding: 15px 20px;
            }

            .navbar h1 {
                font-size: 22px;
            }

            .back-btn {
                font-size: 14px;
                padding: 8px 16px;
            }

            #editor-container {
                height: 300px;
            }

            .form-group label {
                font-size: 15px;
            }

            .form-group input {
                padding: 10px;
                font-size: 14px;
            }

            .btn {
                padding: 10px 20px;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 10px;
            }

            .letter-form-card {
                padding: 20px 15px;
            }

            .navbar {
                padding: 12px 15px;
            }

            .navbar h1 {
                font-size: 20px;
            }

            .back-btn {
                font-size: 13px;
                padding: 7px 14px;
            }

            #editor-container {
                height: 250px;
            }

            .form-group label {
                font-size: 14px;
            }

            .form-group input {
                padding: 9px;
                font-size: 13px;
            }

            .btn {
                padding: 9px 18px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <h1>✍️ Write a Love Letter</h1>
        <div class="navbar-right">
            <a href="{{ route('love-letters.index') }}" class="back-btn">← Back to Letter Box</a>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container">
        <div class="letter-form-card">
            <div class="form-header">
                <h2>Transmit Your Emotions Through Letters 💌</h2>
                <p>Pour your heart out to {{ $partner->name }}</p>
            </div>

            @if ($errors->any())
                <div class="error-message">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('love-letters.store') }}" method="POST" id="letterForm">
                @csrf

                <!-- Letter Title -->
                <div class="form-group">
                    <label for="title">Letter Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" 
                           placeholder="e.g., Forever Yours, My Dearest..." required>
                    <div class="help-text">This will be the only thing visible before the letter is opened</div>
                </div>

                <!-- Letter Content (Rich Text Editor) -->
                <div class="form-group">
                    <label for="content">Your Letter *</label>
                    <div id="editor-container"></div>
                    <input type="hidden" id="content" name="content" required>
                    <div class="help-text">Let your heart speak. Use the formatting tools to make it beautiful.</div>
                </div>

                <!-- Delivery Date & Time -->
                <div class="form-group">
                    <label for="scheduled_delivery_at">Delivery Date & Time *</label>
                    <input type="datetime-local" id="scheduled_delivery_at" name="scheduled_delivery_at" 
                           min="{{ $minDeliveryDateTimeString }}" value="{{ old('scheduled_delivery_at', $minDeliveryDateTimeString) }}" required>
                    <div class="help-text">
                        Minimum wait time: {{ $minDeliveryText }}. Your letter will be delivered at this exact date and time, and only then will {{ $partner->name }} be able to read it.
                    </div>
                </div>

                <!-- Romantic Quote -->
                <div class="romantic-quote">
                    <p>"The best love letters are written from the heart, not from the mind." 💕</p>
                </div>

                <!-- Submit Button -->
                <div class="submit-section">
                    <button type="submit" class="submit-btn">Send Letter with Love 💌</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quill.js JavaScript -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        // Initialize Quill editor with romantic theme
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            placeholder: 'My dearest love,\n\nStart writing your heart out here...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'font': [] }],
                    [{ 'align': [] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['blockquote'],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    ['clean']
                ]
            }
        });

        // Restore old content if validation fails
        var oldContent = @json(old('content'));
        if (oldContent) {
            quill.root.innerHTML = oldContent;
        }

        // Form submission handler
        document.getElementById('letterForm').addEventListener('submit', function(e) {
            // Get HTML content from Quill editor
            var content = quill.root.innerHTML;
            
            // Check if content is empty (only whitespace or empty tags)
            var text = quill.getText().trim();
            if (text.length === 0) {
                e.preventDefault();
                alert('Please write your letter content before sending!');
                return false;
            }
            
            // Set the content to hidden field
            document.getElementById('content').value = content;
        });
    </script>
</body>
</html>
