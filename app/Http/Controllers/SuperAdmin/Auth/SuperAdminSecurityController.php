<?php

namespace App\Http\Controllers\SuperAdmin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class SuperAdminSecurityController extends Controller
{
    public function security()
    {
        $user = Auth::guard('super_admin')->user();

        if (! $user) {
            return redirect()->route('super-admin.login');
        }

        $secret = null;
        $qrCodeUrl = null;

        if (! $user->super_admin_two_factor_enabled) {
            if (! $user->super_admin_two_factor_secret) {
                $google2fa = new Google2FA();

                $user->super_admin_two_factor_secret =
                    $google2fa->generateSecretKey();

                $user->save();
            }

            $secret = $user->super_admin_two_factor_secret;

            $google2fa = new Google2FA();

            $qrContent = $google2fa->getQRCodeUrl(
                config('app.name', 'School Management'),
                $user->email,
                $secret
            );

            $renderer = new ImageRenderer(
                new RendererStyle(220),
                new SvgImageBackEnd()
            );

            $writer = new Writer($renderer);

            $qrCodeUrl = $writer->writeString($qrContent);
        }

        return view('super-admin.settings.security', [
            'user' => $user,
            'secret' => $secret,
            'qrCodeUrl' => $qrCodeUrl,
        ]);
    }

    public function enable(Request $request)
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = Auth::guard('super_admin')->user();

        if (! $user || ! $user->super_admin_two_factor_secret) {
            return redirect()->route('super-admin.security')
                ->withErrors(['code' => 'Generate a new QR setup first.']);
        }

        $google2fa = new Google2FA();

        if (! $google2fa->verifyKey(
            $user->super_admin_two_factor_secret,
            $request->input('code')
        )) {
            return back()->withErrors([
                'code' => 'Invalid code. Check your authenticator app.',
            ]);
        }

        $user->super_admin_two_factor_enabled = true;
        $user->save();

        $request->session()->put('super_admin_2fa_verified', true);
        $request->session()->forget('super_admin_2fa_pending');

        return redirect()->route('super-admin.dashboard')
            ->with('status', 'Two-factor authentication enabled.');
    }

    public function disable(Request $request)
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = Auth::guard('super_admin')->user();

        if (! $user || ! $user->super_admin_two_factor_enabled ||
            ! $user->super_admin_two_factor_secret) {
            return redirect()->route('super-admin.security');
        }

        $google2fa = new Google2FA();

        if (! $google2fa->verifyKey(
            $user->super_admin_two_factor_secret,
            $request->input('code')
        )) {
            return back()->withErrors([
                'code' => 'Invalid authenticator code.',
            ]);
        }

        // Clear the existing secret so a new QR setup is required.
        $user->super_admin_two_factor_enabled = false;
        $user->super_admin_two_factor_secret = null;
        $user->save();

        $request->session()->forget('super_admin_2fa_verified');

        return redirect()->route('super-admin.security')
            ->with('status', '2FA disabled. Set it up again to access the dashboard.');
    }
}
