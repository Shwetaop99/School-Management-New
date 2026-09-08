<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorController extends Controller
{
    /**
     * Show the 2FA setup page.
     */
    public function showSetup()
{
    $user = Auth::user();

    $google2fa = new Google2FA();

    // Generate a secret only if the user doesn't have one.
    if (!$user->two_factor_secret) {
        $secret = $google2fa->generateSecretKey();

        $user->two_factor_secret = $secret;
        $user->save();
    } else {
        $secret = $user->two_factor_secret;
    }

    // Generate Google Authenticator URL.
    $qrCodeUrl = $google2fa->getQRCodeUrl(
        'Gurukul Vidyalaya',
        $user->email,
        $secret
    );

    // Convert the authentication URL into an SVG QR code.
    $renderer = new ImageRenderer(
        new RendererStyle(220),
        new SvgImageBackEnd()
    );

    $writer = new Writer($renderer);

    $qrCode = $writer->writeString($qrCodeUrl);

    return view('admin.auth.2fa-setup', [
        'secret' => $secret,
        'qrCodeUrl' => $qrCode,
    ]);
}

    /**
     * Verify the 6-digit authenticator code.
     */
    public function verify()
    {
        // We'll implement this in the next step.
    }
}