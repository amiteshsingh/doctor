<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\FirebaseNotification;

class SupportController extends Controller
{
    public function index()
    {
        $tickets = DB::table('support_tickets')
            ->join('users', 'support_tickets.user_id', '=', 'users.id')
            ->select('support_tickets.*', 'users.name as user_name', 'users.email as user_email')
            ->orderByDesc('support_tickets.updated_at')
            ->get();

        $messages = DB::table('support_messages')
            ->whereIn('ticket_id', $tickets->pluck('id'))
            ->orderBy('created_at')
            ->get()
            ->groupBy('ticket_id');

        return view('admin.support.index', compact('tickets', 'messages'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate(['reply' => 'required|string']);

        $ticket = DB::table('support_tickets')->where('id', $id)->first();
        if (!$ticket) return back()->with('error', 'Ticket not found.');

        DB::table('support_messages')->insert([
            'ticket_id'  => $id,
            'sender'     => 'admin',
            'message'    => $request->reply,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('support_tickets')->where('id', $id)->update([
            'reply'      => $request->reply,
            'status'     => 'replied',
            'updated_at' => now(),
        ]);

        // FCM notification to user
        $user = DB::table('users')->where('id', $ticket->user_id)->first();
        if ($user && $user->fcm_token) {
            FirebaseNotification::send(
                $user->fcm_token,
                '✅ Support Reply',
                'Aapki support request ka jawab aa gaya hai. App mein dekhen!',
                ['type' => 'support_reply', 'ticket_id' => (string)$id, 'screen' => 'Support']
            );
        }

        DB::table('notification_logs')->insert([
            'user_id'     => $ticket->user_id,
            'title'       => '✅ Support Reply',
            'message'     => 'Aapki support request ka jawab aa gaya hai. App mein dekhen!',
            'target'      => 'specific',
            'target_type' => 'user',
            'sent_count'  => 1,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return back()->with('success', 'Reply sent successfully.');
    }

    public function close($id)
    {
        DB::table('support_tickets')->where('id', $id)->update([
            'status'     => 'closed',
            'updated_at' => now(),
        ]);
        return back()->with('success', 'Ticket closed.');
    }
}
