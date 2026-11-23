<?php

namespace App\Http\Controllers\Damage;

use App\Http\Controllers\Controller;
use App\Models\Damage\DamageRecord;
use App\Models\Damage\VerificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DamageCertificateController extends Controller
{
    /**
     * Public Verification Page accessed via scanning certificate QR code
     * Route: /verify-certificate/{uuid}
     */
    public function verify(Request $request, string $uuid)
    {
        $record = DamageRecord::with(['zone', 'street'])
            ->where('certificate_uuid', $uuid)
            ->first();

        // Log the verification attempt
        VerificationLog::create([
            'certificate_uuid' => $uuid,
            'source' => 'qr_scan_public',
            'requester_ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'notes' => $record ? 'استعلام ناجح عن إفادة حصر أضرار' : 'استعلام عن رمز إفادة غير صالح أو غير موجود',
        ]);

        if (!$record) {
            return view('damage.certificate.invalid', compact('uuid'));
        }

        // Mask citizen national ID for public privacy (e.g., 400****21)
        $maskedId = null;
        if (!empty($record->citizen_national_id)) {
            $id = (string) $record->citizen_national_id;
            $len = strlen($id);
            if ($len >= 6) {
                $maskedId = substr($id, 0, 3) . str_repeat('*', $len - 5) . substr($id, -2);
            } else {
                $maskedId = '****';
            }
        }

        return view('damage.certificate.verify', compact('record', 'maskedId'));
    }

    /**
     * Print official certificate for internal ERP or Citizen Portal
     * Route: /damage/records/{id}/certificate
     */
    public function printCertificate($id)
    {
        $record = DamageRecord::with(['zone', 'street', 'pricingCriteria'])
            ->findOrFail($id);

        $verificationUrl = route('damage.verify-certificate', $record->certificate_uuid);

        // QR Code URL (QuickChart / QRServer for high-res crisp printing)
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&format=svg&data=' . urlencode($verificationUrl);

        return view('damage.certificate.print', compact('record', 'verificationUrl', 'qrUrl'));
    }
}
