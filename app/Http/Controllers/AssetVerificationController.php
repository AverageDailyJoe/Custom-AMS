<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetVerificationController extends Controller
{
    public function show($token)
    {
        $asset = Asset::where('qr_token', $token)->firstOrFail();

        return view('asset-verify', compact('asset'));
    }
}
