<?php

namespace App\Http\Controllers;

use App\Models\Notifications;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function clear(Request $request)
    {
        // Assuming you want to clear only for the logged-in user
        Notifications::where('idAkun', session('user_data')->idAkun)->delete();
        return back()->with('success', 'Semua notifikasi telah dihapus.');
    }

    public function markAllRead(Request $request)
    {
        Notifications::where('idAkun', session('user_data')->idAkun)
            ->where('read', false)
            ->update(['read' => true]);
        return back()->with('success', 'Semua notifikasi telah dihapus.');
    }
}
