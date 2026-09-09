<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $pendingUsers = User::where('is_approved', false)
            ->where('role', '!=', 'admin')
            ->latest()
            ->get();

        $approvedUsers = User::where('is_approved', true)
            ->where('role', '!=', 'admin')
            ->latest()
            ->get();

        return view('admin.users.index', compact('pendingUsers', 'approvedUsers'));
    }

    public function approve(User $user): RedirectResponse
    {
        $user->update(['is_approved' => true]);

        return redirect()->route('admin.users.index')
            ->with('success', "Akun {$user->name} ({$user->email}) berhasil disetujui.");
    }

    public function reject(User $user): RedirectResponse
    {
        $name = $user->name;
        $email = $user->email;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Akun {$name} ({$email}) berhasil ditolak dan dihapus.");
    }
}
