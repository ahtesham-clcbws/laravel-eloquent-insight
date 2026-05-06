<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\OtpVerifications;
use Illuminate\Support\Facades\Log;

class Msg91Service
{
    protected $authKey;
    protected $senderId;
    protected $templateId;
 
    /**
     * Centralized TRAI Approved Template
     * The only thing we change is the OTP, everything else is a traced copy of the approved message.
     */
    const OTP_TEMPLATE = "Dear user OTP for sign up to www.careerwithoutbarrier.com is {#var#}. valid for 10 minutes. Do not share this OTP Regards CAREER without BARRIER Management";
 
    public function __construct($authKey = null, $senderId = null, $templateId = null)
    {
        $this->authKey = $authKey ?: env('MSG91_AUTH_KEY');
        $this->senderId = $senderId ?: env('MSG91_SENDER_ID', 'SQSCWB');
        $this->templateId = $templateId ?: env('MSG91_OTP_TEMPLATE_ID');
    }
 
    /**
     * Get the formatted OTP message based on the approved TRAI template.
     */
    public function getFormattedMessage($otp)
    {
        return str_replace('{#var#}', $otp, self::OTP_TEMPLATE);
    }
 
    /**
     * Send OTP via MSG91 Flow API
     * 
     * @param string|array $numbers
     * @param string|int $otp
     * @param string|null $templateId
     * @return bool
     */
    public function sendSms($numbers, $otp, $templateId = null)
    {
        if (empty($this->authKey)) {
            Log::error('MSG91 Auth Key is missing in .env');
            return false;
        }
 
        $activeTemplateId = $templateId ?: $this->templateId;
        $numberList = is_array($numbers) ? $numbers : explode(',', $numbers);
 
        foreach ($numberList as $number) {
            // Clean number to exactly 10 digits for database and internal matching
            $rawNumber = preg_replace('/[^0-9]/', '', $number);
            $tenDigitNumber = (strlen($rawNumber) > 10) ? substr($rawNumber, -10) : $rawNumber;
            
            // Save to database using the strictly 10-digit number
            $this->saveOtpToDatabase($tenDigitNumber, $otp);

            // Add country code for MSG91 API call
            $apiNumber = '91' . $tenDigitNumber;

            // Using RAW PHP cURL to match your working terminal test EXACTLY
            $payload = json_encode([
                'template_id' => (string)$activeTemplateId,
                'short_url' => '0',
                'short_url_expiry' => '300',
                'realTimeResponse' => '1',
                'recipients' => [
                    [
                        'mobiles' => (string)$apiNumber,
                        'var' => (string)$otp,
                    ]
                ]
            ]);

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => "https://control.msg91.com/api/v5/flow",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_HTTPHEADER => [
                    "accept: application/json",
                    "authkey: " . $this->authKey,
                    "content-type: application/json"
                ],
            ]);

            $responseBody = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                Log::error('MSG91 cURL Error', ['error' => $curlError, 'number' => $apiNumber]);
                echo "cURL Error: " . $curlError . "\n";
                return false;
            }

            $body = json_decode($responseBody, true);
            $isError = ($httpCode >= 400) || 
                       (isset($body['type']) && $body['type'] === 'error') || 
                       (isset($body['status']) && $body['status'] === 'fail');

            if ($isError) {
                Log::error('MSG91 API Error', [
                    'status' => $httpCode,
                    'body' => $responseBody,
                    'number' => $apiNumber
                ]);
                // Log::error("API Error: " . $responseBody);
                return false;
            } else {
                // Log::info("API Response: " . $responseBody);
            }
        }
 
        return true;
    }

    /**
     * Log OTP to database for verification
     */
    protected function saveOtpToDatabase($number, $otp)
    {
        try {
            $otpVerifications               = new OtpVerifications();
            $otpVerifications->type         = 'mobile';
            $otpVerifications->credential   = (string)$number;
            $otpVerifications->otp          = (string)$otp;
            $otpVerifications->status       = 0;
            $otpVerifications->save();
        } catch (\Throwable $th) {
            Log::error('Failed to save OTP to database', [
                'error' => $th->getMessage(),
                'number' => $number,
                'otp' => $otp
            ]);
        }
    }
}
