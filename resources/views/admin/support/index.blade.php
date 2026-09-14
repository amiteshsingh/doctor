@extends('admin.layout.app')

@section('content')
<style>
.sup-card { background:#fff; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,.07); overflow:hidden; margin-bottom:20px; }
.sup-head { background:linear-gradient(135deg,#667eea,#764ba2); padding:16px 22px; color:#fff; display:flex; align-items:center; justify-content:space-between; }
.sup-head h5 { margin:0; font-weight:800; font-size:16px; }
.badge-open   { background:#fff3cd; color:#856404; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }
.badge-replied{ background:#d1e7dd; color:#0a3622; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }
.badge-closed { background:#e2e3e5; color:#41464b; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }
.msg-user  { background:#f0f4ff; border-radius:12px 12px 12px 0; padding:10px 14px; margin-bottom:8px; max-width:80%; }
.msg-admin { background:#e6fff5; border-radius:12px 12px 0 12px; padding:10px 14px; margin-bottom:8px; max-width:80%; margin-left:auto; }
.msg-label { font-size:10px; font-weight:700; margin-bottom:3px; }
.msg-text  { font-size:13px; color:#333; line-height:1.5; }
.msg-time  { font-size:10px; color:#aaa; margin-top:3px; }
.chat-box  { max-height:300px; overflow-y:auto; padding:12px; background:#fafbff; border-radius:12px; margin-bottom:14px; border:1px solid #eee; }
</style>

<div class="page-wrapper">
<div class="content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0" style="font-weight:800;color:#1a1a2e;">🎧 Support Tickets</h4>
            <small class="text-muted">Users ki problems aur queries</small>
        </div>
        <div style="display:flex;gap:10px;">
            <span style="background:#fff3cd;padding:6px 14px;border-radius:8px;font-size:13px;">
                Open: <strong>{{ $tickets->where('status','open')->count() }}</strong>
            </span>
            <span style="background:#d1e7dd;padding:6px 14px;border-radius:8px;font-size:13px;">
                Replied: <strong>{{ $tickets->where('status','replied')->count() }}</strong>
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse($tickets as $ticket)
    <div class="sup-card">
        <div class="sup-head">
            <h5>🎫 {{ $ticket->subject }}</h5>
            <span class="badge-{{ $ticket->status }}">{{ ucfirst($ticket->status) }}</span>
        </div>
        <div style="padding:18px 22px;">
            <div class="d-flex gap-3 mb-3 flex-wrap">
                <span style="font-size:13px;"><strong>👤</strong> {{ $ticket->user_name }}</span>
                <span style="font-size:13px;"><strong>✉️</strong> {{ $ticket->user_email }}</span>
                <span style="font-size:12px;color:#888;">{{ \Carbon\Carbon::parse($ticket->created_at)->format('d M Y, h:i A') }}</span>
            </div>

            {{-- Conversation Thread --}}
            <div class="chat-box">
                @forelse($messages[$ticket->id] ?? [] as $msg)
                    @if($msg->sender === 'user')
                    <div class="msg-user">
                        <div class="msg-label" style="color:#667eea;">👤 {{ $ticket->user_name }}</div>
                        <div class="msg-text">{{ $msg->message }}</div>
                        <div class="msg-time">{{ \Carbon\Carbon::parse($msg->created_at)->format('d M, h:i A') }}</div>
                    </div>
                    @else
                    <div class="msg-admin">
                        <div class="msg-label" style="color:#00b074;">✅ Admin</div>
                        <div class="msg-text">{{ $msg->message }}</div>
                        <div class="msg-time">{{ \Carbon\Carbon::parse($msg->created_at)->format('d M, h:i A') }}</div>
                    </div>
                    @endif
                @empty
                    <div class="text-center text-muted" style="font-size:13px;padding:10px;">Koi message nahi.</div>
                @endforelse
            </div>

            @if($ticket->status !== 'closed')
            <form action="{{ route('admin.support.reply', $ticket->id) }}" method="POST">
                @csrf
                <div class="d-flex gap-2">
                    <input type="text" name="reply" class="form-control" placeholder="Reply likhein..." required
                        style="border-radius:10px;font-size:13px;">
                    <button type="submit" class="btn btn-primary" style="border-radius:10px;white-space:nowrap;font-weight:700;">
                        📤 Send
                    </button>
                    <a href="{{ route('admin.support.close', $ticket->id) }}"
                        onclick="return confirm('Close this ticket?')"
                        class="btn btn-secondary" style="border-radius:10px;white-space:nowrap;font-weight:700;">
                        ✖ Close
                    </a>
                </div>
            </form>
            @endif
        </div>
    </div>
    @empty
    <div class="text-center py-5 text-muted">
        <div style="font-size:48px;">🎉</div>
        <div style="font-size:15px;margin-top:10px;">Koi support ticket nahi hai abhi.</div>
    </div>
    @endforelse

</div>
</div>
@endsection
