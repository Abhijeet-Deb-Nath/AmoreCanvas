<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dream - AmoreCanvas</title>
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

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 20px 15px;
            }

            .container {
                padding: 25px 20px;
                border-radius: 15px;
            }

            .header h1 {
                font-size: 28px;
            }

            .header p {
                font-size: 14px;
            }

            .form-group label {
                font-size: 15px;
            }

            .form-group input[type="text"],
            .form-group textarea {
                padding: 10px 12px;
                font-size: 14px;
            }

            .buttons {
                flex-direction: column;
                gap: 12px;
            }

            .btn {
                padding: 12px;
                font-size: 15px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 15px 10px;
            }

            .container {
                padding: 20px 15px;
            }

            .header h1 {
                font-size: 24px;
            }

            .header p {
                font-size: 13px;
            }

            .form-group {
                margin-bottom: 20px;
            }

            .form-group label {
                font-size: 14px;
            }

            .form-group input[type="text"],
            .form-group textarea {
                padding: 9px 10px;
                font-size: 13px;
            }

            .btn {
                padding: 11px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✨ Edit Your Dream ✨</h1>
            <p>Refine the details of your beautiful vision</p>
        </div>

        <form action="{{ route('dreams.update', $dream->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="heading">
                    Heading <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    id="heading" 
                    name="heading" 
                    value="{{ old('heading', $dream->heading) }}"
                    placeholder="e.g., Paris Under the Stars"
                    required
                >
                @error('heading')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="title">
                    Title <span class="optional">(optional)</span>
                </label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    value="{{ old('title', $dream->title) }}"
                    placeholder="e.g., Our Romantic Getaway"
                >
                @error('title')
                    <div class="error">{{ $message }}</div>
                @enderror>
            </div>

            <div class="form-group">
                <label for="description">
                    Description <span class="required">*</span>
                </label>
                <textarea 
                    id="description" 
                    name="description" 
                    placeholder="Describe your dream..."
                    required
                >{{ old('description', $dream->description) }}</textarea>
                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="place">
                    Place <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    id="place" 
                    name="place" 
                    value="{{ old('place', $dream->place) }}"
                    placeholder="e.g., Eiffel Tower, Paris"
                    required
                >
                @error('place')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="buttons">
                <a href="{{ route('dreams.show', $dream->id) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Dream ✨</button>
            </div>
        </form>
    </div>
</body>
</html>
