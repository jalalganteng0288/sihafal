<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotifikasiController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $notifikasi = Notifikasi::where('user_id', $user->id)
            ->orderByRaw('is_dibaca ASC')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifikasi.index', compact('notifikasi'));
    }

    public function markRead(Notifikasi $notifikasi): RedirectResponse
    {
        abort_if($notifikasi->user_id !== Auth::id(), 403);

        $notifikasi->update([
            'is_dibaca' => true,
            'dibaca_at' => now(),
        ]);

        return back()->with('success', 'Notifikasi ditandai sebagai dibaca.');
    }

    public function markAllRead(): RedirectResponse
    {
        Notifikasi::where('user_id', Auth::id())
            ->where('is_dibaca', false)
            ->update([
                'is_dibaca' => true,
                'dibaca_at' => now(),
            ]);

        return back()->with('success', 'Semua notifikasi ditandai sebagai dibaca.');
    }
}
