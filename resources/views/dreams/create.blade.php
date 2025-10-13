<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Whisper a New Dream - AmoreCanvas</title>
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
            padding: 40px 20px;
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .header {
            text-align: center;
            margin-bottom: 35px;
        }

        .header h1 {
            color: #764ba2;
            font-size: 36px;
            margin-bottom: 10px;
        }

        .header p {
            color: #666;
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 16px;
        }

        .form-group label .required {
            color: #dc3545;
        }

        .form-group label .optional {
            color: #999;
            font-weight: normal;
            font-size: 14px;
        }

        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            font-family: 'Georgia', serif;
            transition: all 0.3s;
        }

        .form-group input[type="text"]:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #764ba2;
            box-shadow: 0 0 0 3px rgba(118, 75, 162, 0.1);
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .error {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }

        .buttons {
            display: flex;
            gap: 15px;
            margin-top: 35px;
        }

        .btn {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(118, 75, 162, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
        }

        .help-text {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✨ Whisper a New Dream ✨</h1>
            <p>Share a beautiful moment you wish to live</p>
        </div>

        <form action="{{ route('dreams.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="heading">
                    Heading <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    id="heading" 
                    name="heading" 
                    value="{{ old('heading') }}"
                    placeholder="e.g., Paris Under the Stars"
                    required
                >
                @error('heading')
                    <div class="error">{{ $message }}</div>
                @enderror
                <div class="help-text">A captivating title for your dream</div>
            </div>

            <div class="form-group">
                <label for="title">
                    Title <span class="optional">(optional)</span>
                </label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    value="{{ old('title') }}"
                    placeholder="e.g., Our Romantic Getaway"
                >
                @error('title')
                    <div class="error">{{ $message }}</div>
                @enderror
                <div class="help-text">An additional subtitle if you wish</div>
            </div>

            <div class="form-group">
                <label for="description">
                    Description <span class="required">*</span>
                </label>
                <textarea 
                    id="description" 
                    name="description" 
                    placeholder="Describe your dream... What would you do? How would it feel? Paint the picture with your words..."
                    required
                >{{ old('description') }}</textarea>
                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
                <div class="help-text">Share the details of this beautiful dream</div>
            </div>

            <div class="form-group">
                <label for="place">
                    Place <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    id="place" 
                    name="place" 
                    value="{{ old('place') }}"
                    placeholder="e.g., Eiffel Tower, Paris"
                    required
                >
                @error('place')
                    <div class="error">{{ $message }}</div>
                @enderror
                <div class="help-text">Where will this dream come alive?</div>
            </div>

            <div class="buttons">
                <a href="{{ route('dreams.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Whisper This Dream ✨</button>
            </div>
        </form>
    </div>
</body>
</html>
