<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $letter->title }} - AmoreCanvas</title>
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
            font-size: 24px;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .back-btn, .action-btn {
            padding: 10px 20px;
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

        .action-btn.download {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .action-btn.memory {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .action-btn.delete {
            background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
        }

        .back-btn:hover, .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }

        .letter-container {
            background: white;
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .letter-container::before {
            content: '💌';
            position: absolute;
            font-size: 200px;
            top: -50px;
            right: -50px;
            opacity: 0.05;
            transform: rotate(15deg);
        }

        .letter-header {
            text-align: center;
            padding-bottom: 30px;
            border-bottom: 3px double #e91e63;
            margin-bottom: 40px;
        }

        .letter-header h1 {
            font-size: 36px;
            color: #e91e63;
            margin-bottom: 20px;
        }

        .letter-meta {
            display: flex;
            justify-content: space-around;
            margin-top: 15px;
            color: #666;
            font-size: 14px;
        }

        .letter-meta div {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .letter-meta .label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #e91e63;
        }

        .letter-content {
            line-height: 2;
            font-size: 18px;
            color: #333;
            position: relative;
            z-index: 1;
        }

        .letter-content p {
            margin-bottom: 20px;
        }

        .letter-signature {
            text-align: right;
            margin-top: 50px;
            font-style: italic;
            color: #666;
            font-size: 18px;
        }

        .action-section {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #eee;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .modal-header h2 {
            color: #e91e63;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .modal-header p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 16px;
            color: #333;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .form-group textarea {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-family: 'Georgia', serif;
            resize: vertical;
            min-height: 100px;
        }

        .form-group textarea:focus {
            outline: none;
            border-color: #e91e63;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 25px;
        }

        .modal-btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .modal-btn.cancel {
            background: #ccc;
            color: #333;
        }

        .modal-btn.submit {
            background: linear-gradient(135deg, #e91e63 0%, #c2185b 100%);
            color: white;
        }

        .modal-btn:hover {
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
            }

            .navbar h1 {
                font-size: 22px;
            }

            .navbar-right {
                flex-wrap: wrap;
                gap: 10px;
            }

            .action-btn,
            .back-btn {
                font-size: 14px;
                padding: 8px 16px;
            }

            .letter-container {
                padding: 30px 20px;
            }

            .letter-header h1 {
                font-size: 28px;
            }

            .letter-header .from {
                font-size: 15px;
            }

            .letter-header .to {
                font-size: 15px;
            }

            .letter-content {
                font-size: 16px;
                padding: 25px 20px;
            }

            .action-section {
                flex-wrap: wrap;
                gap: 10px;
            }

            .action-section button,
            .action-section form button {
                padding: 10px 20px;
                font-size: 14px;
            }

            .delivery-info {
                padding: 15px;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .navbar {
                padding: 12px 15px;
                flex-wrap: wrap;
            }

            .navbar h1 {
                font-size: 20px;
                width: 100%;
                margin-bottom: 10px;
            }

            .navbar-right {
                width: 100%;
                justify-content: space-between;
            }

            .action-btn,
            .back-btn {
                font-size: 13px;
                padding: 7px 14px;
            }

            .letter-container {
                padding: 25px 15px;
            }

            .letter-header h1 {
                font-size: 24px;
            }

            .letter-header .from,
            .letter-header .to {
                font-size: 14px;
            }

            .letter-content {
                font-size: 15px;
                padding: 20px 15px;
            }

            .action-section {
                flex-direction: column;
                gap: 8px;
            }

            .action-section button,
            .action-section form button {
                width: 100%;
                padding: 9px 18px;
                font-size: 13px;
            }

            .delivery-info {
                padding: 12px;
                font-size: 13px;
            }

            .modal-content {
                padding: 25px 20px;
                width: 95%;
            }

            .modal-content h3 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <h1>💌 Love Letter</h1>
        <div class="navbar-right">
            <a href="{{ route('love-letters.download', $letter->id) }}" class="action-btn download">
                📥 Download
            </a>
            <a href="{{ route('love-letters.index') }}" class="back-btn">← Back</a>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container">
        @if(session('success'))
            <div style="background: #4CAF50; color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #f44336; color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                {{ session('error') }}
            </div>
        @endif

        <div class="letter-container">
            <!-- Letter Header -->
            <div class="letter-header">
                <h1>{{ $letter->title }}</h1>
                <div class="letter-meta">
                    <div>
                        <span class="label">From</span>
                        <span>{{ $letter->sender->name }}</span>
                    </div>
                    <div>
                        <span class="label">Written</span>
                        <span>{{ $letter->created_at->format('F j, Y \a\t g:i A') }}</span>
                    </div>
                    <div>
                        <span class="label">Delivered</span>
                        <span>{{ $letter->delivered_at->format('F j, Y \a\t g:i A') }}</span>
                    </div>
                </div>
            </div>

            <!-- Letter Content -->
            <div class="letter-content">
                {!! $letter->content !!}
            </div>

            <!-- Letter Signature -->
            <div class="letter-signature">
                With all my love,<br>
                {{ $letter->sender->name }} 💕
            </div>
        </div>

        <!-- Action Section -->
        <div class="action-section">
            @if(!$letter->is_in_memory_lane)
                <button onclick="showMemoryLaneModal()" class="action-btn memory">
                    💝 Add to Memory Lane
                </button>
                <button onclick="showPermanentDeleteModal()" class="action-btn delete">
                    🗑️ Delete Permanently
                </button>
            @else
                <div style="text-align: center; padding: 15px 20px; background: linear-gradient(135deg, #fff5f8 0%, #ffe6f0 100%); border-radius: 10px; margin-bottom: 15px;">
                    <p style="color: #666; font-style: italic; margin-bottom: 10px;">
                        ✨ This letter has been preserved in your Memory Lane 💕
                    </p>
                </div>
                <button onclick="showMemoryLaneDeleteModal()" class="action-btn delete">
                    🗑️ Delete from Memory Lane & Letter Box
                </button>
            @endif
        </div>
    </div>

    <!-- Add to Memory Lane Modal -->
    <div id="memoryLaneModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add to Memory Lane 💕</h2>
                <p>Add a note to remember this special moment</p>
            </div>
            <form action="{{ route('love-letters.add-to-memory-lane', $letter->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="memory_note">Memory Note *</label>
                    <textarea id="memory_note" name="memory_note" placeholder="Write about what this letter means to you..." required maxlength="500"></textarea>
                    <div style="font-size: 12px; color: #999; margin-top: 5px;">Maximum 500 characters</div>
                </div>
                <div class="modal-actions">
                    <button type="button" onclick="closeMemoryLaneModal()" class="modal-btn cancel">Cancel</button>
                    <button type="submit" class="modal-btn submit">Add to Memory Lane</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Permanent Delete Confirmation Modal -->
    <div id="permanentDeleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>⚠️ Permanent Delete</h2>
                <p>Are you absolutely sure?</p>
            </div>
            <div style="text-align: center; color: #666; margin: 20px 0; line-height: 1.6;">
                <p><strong>This letter will be deleted forever</strong> and cannot be recovered.</p>
                <p style="margin-top: 10px;">If you want to keep the memory, add it to Memory Lane instead.</p>
            </div>
            <div class="modal-actions">
                <button type="button" onclick="closePermanentDeleteModal()" class="modal-btn cancel">Cancel</button>
                <button type="button" onclick="closePermanentDeleteModal(); showMemoryLaneModal();" class="modal-btn submit">
                    Add to Memory Lane
                </button>
                <form action="{{ route('love-letters.permanent-delete', $letter->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="modal-btn" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
                        Delete Forever
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Memory Lane Delete Confirmation Modal -->
    <div id="memoryLaneDeleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>⚠️ Delete from Memory Lane</h2>
                <p>This will remove the letter from both places</p>
            </div>
            <div style="text-align: center; color: #666; margin: 20px 0; line-height: 1.6;">
                <p><strong>This will delete:</strong></p>
                <p style="margin-top: 10px;">✗ The letter from Letter Box<br>✗ The memory from Memory Lane</p>
                <p style="margin-top: 10px; color: #e74c3c;">This action cannot be undone.</p>
            </div>
            <div class="modal-actions">
                <button type="button" onclick="closeMemoryLaneDeleteModal()" class="modal-btn cancel">Cancel</button>
                <form action="{{ route('love-letters.destroy', $letter->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="modal-btn" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
                        Delete Both
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showMemoryLaneModal() {
            document.getElementById('memoryLaneModal').classList.add('active');
        }

        function closeMemoryLaneModal() {
            document.getElementById('memoryLaneModal').classList.remove('active');
        }

        function showPermanentDeleteModal() {
            document.getElementById('permanentDeleteModal').classList.add('active');
        }

        function closePermanentDeleteModal() {
            document.getElementById('permanentDeleteModal').classList.remove('active');
        }

        function showMemoryLaneDeleteModal() {
            document.getElementById('memoryLaneDeleteModal').classList.add('active');
        }

        function closeMemoryLaneDeleteModal() {
            document.getElementById('memoryLaneDeleteModal').classList.remove('active');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
            }
        }
    </script>
</body>
</html>
