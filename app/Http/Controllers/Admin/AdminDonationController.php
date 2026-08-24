<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;

class AdminDonationController extends Controller
{
    public function index()
    {
        $donations = Donation::with('user')
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => Donation::where('status', 'confirmed')->sum('amount'),
            'pending' => Donation::where('status', 'pending')->count(),
            'confirmed' => Donation::where('status', 'confirmed')->count(),
            'failed' => Donation::where('status', 'failed')->count(),
        ];

        return view('admin.donations.index', compact('donations', 'stats'));
    }

}
