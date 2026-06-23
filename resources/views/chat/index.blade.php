<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #F4F6F9; font-family: 'Segoe UI', sans-serif; }
        .page-header {
            background: #fff;
            padding: 16px 28px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .chat-list-item {
            background: #fff;
            border-radius: 12px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 10px;
            text-decoration: none;
            color: inherit;
            transition: box-shadow 0.2s, transform 0.2s;
            border: 1px solid #f0f0f0;
        }
        .chat-list-item:hover {
            box-shadow: 0 4px 14px rgba(0,0,0,0.08);
            transform: translateY(-1px);
            color: inherit;
        }
        .avatar {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: #E8F8EF;
            color: #00AA5B;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        .chat-meta {
            flex: 1;
            min-width: 0;
        }
        .chat-order-id {
            font-size: 0.75rem;
            color: #adb5bd;
        }
        .chat-route {
            font-weight: 600;
            font-size: 0.9rem;
            color: #1a1a2e;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .chat-preview {
            font-size: 0.8rem;
            color: #6c757d;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .unread-badge {
            background: #00AA5B;
            color: #fff;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 3px 9px;
            flex-shrink: 0;
        }
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        .dot-pending   { background: #FFC107; }
        .dot-completed { background: #00AA5B; }
        .dot-cancelled { background: #DC3545; }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #adb5bd;
        }
        .empty-state i { font-size: 3rem; margin-bottom: 12px; display: block; }
    </style>
</head>
<body>

<div class="page-header">
    <div>
        <h5 class="mb-0 fw-bold">Pesan</h5>
        <small class="text-muted">Chat per order</small>
    </div>
    <a href="{{ $role === 'driver' ? route('driver.dashboard') : route('customer.dashboard') }}"
       class="btn btn-sm btn-outline-secondary">
        Kembali
    </a>
</div>

<div class="container py-4" style="max-width: 700px;">

    @if($orders->isEmpty())
        <div class="empty-state">
            <i class="bi bi-chat-dots"></i>
            Belum ada percakapan.
        </div>
    @else
        @foreach($orders as $order)
            @php
                $otherName = $role === 'driver'
                    ? ($order->user->name ?? 'Customer')
                    : ($order->driver->user->name ?? 'Driver');

                $lastMsg = $order->messages->last();
            @endphp

            <a href="{{ route('chat.show', $order->id) }}" class="chat-list-item">
                <div class="avatar">
                    <i class="bi {{ $role === 'driver' ? 'bi-person-fill' : 'bi-car-front-fill' }}"></i>
                </div>

                <div class="chat-meta">
                    <div class="chat-order-id">
                        <span class="status-dot dot-{{ $order->status === 'cancelled' ? 'cancelled' : ($order->status === 'completed' || $order->status === 'paid' ? 'completed' : 'pending') }}"></span>
                        Order #{{ $order->id }}
                    </div>
                    <div class="chat-route">{{ $otherName }}</div>
                    <div class="chat-preview">
                        {{ $lastMsg ? Str::limit($lastMsg->message, 45) : 'Belum ada pesan' }}
                    </div>
                </div>

                @if($order->unread_count > 0)
                    <span class="unread-badge">{{ $order->unread_count }}</span>
                @endif
            </a>
        @endforeach
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>