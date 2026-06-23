<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat Order #{{ $order->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #ECE5DD;
            height: 100dvh;
            display: flex;
            flex-direction: column;
        }

        /* ── Header ── */
        .chat-header {
            background: #075E54;
            color: #fff;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .back-btn {
            color: #fff;
            text-decoration: none;
            font-size: 1.1rem;
            padding: 4px 8px 4px 0;
        }

        .header-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #128C7E;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .header-info { flex: 1; }

        .header-name {
            font-weight: 600;
            font-size: 0.95rem;
        }

        .header-sub {
            font-size: 0.72rem;
            opacity: 0.8;
            margin-top: 1px;
        }

        .header-role {
            font-size: 0.72rem;
            background: rgba(255,255,255,0.2);
            padding: 3px 10px;
            border-radius: 20px;
        }

        /* ── Info Bar ── */
        .info-bar {
            background: #FFF8E1;
            padding: 8px 16px;
            font-size: 0.78rem;
            color: #795548;
            border-bottom: 1px solid #FFE082;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* ── Messages ── */
        .messages-wrap {
            flex: 1;
            overflow-y: auto;
            padding: 16px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .msg-row {
            display: flex;
            margin-bottom: 2px;
        }

        .msg-row.me    { justify-content: flex-end; }
        .msg-row.other { justify-content: flex-start; }

        .bubble {
            max-width: 72%;
            padding: 8px 12px;
            border-radius: 12px;
            font-size: 0.88rem;
            line-height: 1.45;
            position: relative;
            word-break: break-word;
        }

        .msg-row.me .bubble {
            background: #DCF8C6;
            color: #1a1a1a;
            border-bottom-right-radius: 3px;
        }

        .msg-row.other .bubble {
            background: #fff;
            color: #1a1a1a;
            border-bottom-left-radius: 3px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.08);
        }

        .bubble-sender {
            font-size: 0.72rem;
            font-weight: 700;
            color: #00AA5B;
            margin-bottom: 3px;
        }

        .msg-row.me .bubble-sender {
            color: #075E54;
            text-align: right;
        }

        .bubble-time {
            font-size: 0.68rem;
            color: #aaa;
            text-align: right;
            margin-top: 4px;
        }

        .date-divider {
            text-align: center;
            margin: 12px 0;
        }

        .date-divider span {
            background: rgba(225,245,254,0.9);
            color: #546E7A;
            font-size: 0.72rem;
            padding: 4px 12px;
            border-radius: 10px;
        }

        /* ── Input Area ── */
        .input-area {
            background: #F0F0F0;
            padding: 10px 12px;
            display: flex;
            align-items: flex-end;
            gap: 8px;
            flex-shrink: 0;
        }

        .msg-input {
            flex: 1;
            border: none;
            border-radius: 22px;
            padding: 10px 16px;
            font-size: 0.9rem;
            outline: none;
            resize: none;
            max-height: 120px;
            line-height: 1.4;
            background: #fff;
            font-family: inherit;
        }

        .send-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: none;
            background: #075E54;
            color: #fff;
            font-size: 1.1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: background 0.2s;
        }

        .send-btn:hover { background: #128C7E; }
        .send-btn:disabled { background: #adb5bd; cursor: not-allowed; }

        /* ── Typing indicator ── */
        .typing-indicator {
            display: none;
            padding: 0 12px 4px;
            font-size: 0.75rem;
            color: #6c757d;
            font-style: italic;
        }
    </style>
</head>
<body>

{{-- Header --}}
<div class="chat-header">
    <a href="{{ route('chat.index') }}" class="back-btn">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div class="header-avatar">
        @if($as === 'admin') <i class="bi bi-headset"></i>
        @else <i class="bi bi-person"></i> @endif
    </div>
    <div class="header-info">
        <div class="header-name">Order #{{ $order->id }}</div>
        <div class="header-sub">
            {{ Str::limit($order->pickup, 20) }} → {{ Str::limit($order->destination, 20) }}
        </div>
    </div>
    <div class="header-role">
        {{ $as === 'admin' ? '👨‍💼 Admin' : '🧑 Customer' }}
    </div>
</div>

{{-- Info bar --}}
<div class="info-bar">
    <i class="bi bi-info-circle"></i>
    Kendaraan: <strong>{{ $order->vehicle_type }}</strong> &nbsp;·&nbsp;
    Jarak: <strong>{{ number_format($order->distance, 2) }} km</strong> &nbsp;·&nbsp;
    Harga: <strong>Rp {{ number_format($order->price, 0, ',', '.') }}</strong>
</div>

{{-- Messages --}}
<div class="messages-wrap" id="messagesWrap">

    @if($messages->isEmpty())
        <div class="date-divider"><span>Mulai percakapan</span></div>
    @else
        <div class="date-divider"><span>Hari ini</span></div>
    @endif

    @foreach($messages as $msg)
        <div class="msg-row {{ $msg->sender === $as ? 'me' : 'other' }}"
             data-id="{{ $msg->id }}">
            <div class="bubble">
                @if($msg->sender !== $as)
                    <div class="bubble-sender">{{ $msg->sender_name }}</div>
                @endif
                {{ $msg->message }}
                <div class="bubble-time">{{ $msg->created_at->format('H:i') }}</div>
            </div>
        </div>
    @endforeach

</div>

<div class="typing-indicator" id="typingIndicator">mengetik...</div>

{{-- Input --}}
<div class="input-area">
    <textarea
        id="msgInput"
        class="msg-input"
        placeholder="Ketik pesan..."
        rows="1"
    ></textarea>
    <button class="send-btn" id="sendBtn">
        <i class="bi bi-send-fill"></i>
    </button>
</div>

<script>
    const ORDER_ID   = {{ $order->id }};
    const AS         = '{{ $as }}';
    const SENDER_NAME = AS === 'admin' ? 'Admin' : 'Customer';
    const CSRF       = document.querySelector('meta[name="csrf-token"]').content;
    const POLL_URL   = `/chat/${ORDER_ID}/poll?as=${AS}`;
    const SEND_URL   = `/chat/${ORDER_ID}/send`;

    const wrap    = document.getElementById('messagesWrap');
    const input   = document.getElementById('msgInput');
    const sendBtn = document.getElementById('sendBtn');

    // ID pesan terakhir untuk polling
    let lastId = {{ $messages->last()?->id ?? 0 }};

    // ── Scroll ke bawah ──────────────────────────────────────────────────
    function scrollBottom() {
        wrap.scrollTop = wrap.scrollHeight;
    }
    scrollBottom();

    // ── Render bubble pesan ───────────────────────────────────────────────
    function renderMessage(msg) {
        const isMe = msg.sender === AS;
        const row  = document.createElement('div');
        row.className = `msg-row ${isMe ? 'me' : 'other'}`;
        row.dataset.id = msg.id;

        row.innerHTML = `
            <div class="bubble">
                ${!isMe ? `<div class="bubble-sender">${msg.sender_name}</div>` : ''}
                ${escapeHtml(msg.message)}
                <div class="bubble-time">${msg.time}</div>
            </div>
        `;
        wrap.appendChild(row);
        scrollBottom();
    }

    function escapeHtml(str) {
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    // ── Kirim pesan ───────────────────────────────────────────────────────
    async function sendMessage() {
        const text = input.value.trim();
        if (!text) return;

        sendBtn.disabled = true;
        input.value      = '';
        input.style.height = 'auto';

        try {
            const res = await fetch(SEND_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    message:     text,
                    sender:      AS,
                    sender_name: SENDER_NAME,
                }),
            });

            const msg = await res.json();
            renderMessage(msg);
            lastId = msg.id;

        } catch (err) {
            console.error('Gagal kirim:', err);
            input.value = text; // kembalikan teks jika gagal
        } finally {
            sendBtn.disabled = false;
            input.focus();
        }
    }

    // ── Polling pesan baru ────────────────────────────────────────────────
    async function pollMessages() {
        try {
            const res  = await fetch(`${POLL_URL}&last_id=${lastId}`, {
                headers: { 'Accept': 'application/json' }
            });
            const msgs = await res.json();

            msgs.forEach(msg => {
                // Jangan render pesan yang dikirim sendiri (sudah ada)
                if (msg.sender !== AS) {
                    renderMessage(msg);
                }
                if (msg.id > lastId) lastId = msg.id;
            });
        } catch (err) {
            // Silent fail — polling coba lagi nanti
        }
    }

    // Polling setiap 3 detik
    setInterval(pollMessages, 3000);

    // ── Event listeners ───────────────────────────────────────────────────
    sendBtn.addEventListener('click', sendMessage);

    input.addEventListener('keydown', function (e) {
        // Enter kirim, Shift+Enter baris baru
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // Auto-resize textarea
    input.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });
</script>

</body>
</html>