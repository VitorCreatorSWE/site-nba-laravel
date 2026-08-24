<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function index()
    {
        return view('donations.index');
    }

    public function generatePix(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        return back()->with('error', __('messages.pix_not_available'));
    }

    public function confirm(Request $request)
    {
        return back()->with('info', __('messages.donation_pending'));
    }
}