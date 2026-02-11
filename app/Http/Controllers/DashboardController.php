<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        $role = auth()->user()->role;

        return match ($role) {
            'admin' => redirect()->route('admin.users.index'),
            'petugas' => redirect()->route('petugas.approvals.index'),
            default => redirect()->route('peminjam.catalog.index'),
        };
    }
}
