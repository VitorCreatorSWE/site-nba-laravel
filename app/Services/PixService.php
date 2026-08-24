<?php

namespace App\Services;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QROutputInterface;

class PixService
{
    private string $pixKey;
    private string $merchantName;
    private string $merchantCity;

    public function __construct()
    {
        $this->pixKey = config('services.pix.key');
        $this->merchantName = config('services.pix.merchant_name');
        $this->merchantCity = config('services.pix.merchant_city');
    }

    public function generatePayload(float $amount, string $transactionId, string $description = ''): string
    {
        $payload = '';
        $payload .= '00020126'; // Payload Format Indicator
        $payload .= '5800BR';
        $payload .= '59' . $this->formatLength($this->merchantName);
        $payload .= $this->merchantName;
        $payload .= '60' . $this->formatLength($this->merchantCity);
        $payload .= $this->merchantCity;

        // Amount
        if ($amount > 0) {
            $amountStr = number_format($amount, 2, '.', '');
            $payload .= '54' . $this->formatLength($amountStr);
            $payload .= $amountStr;
        }

        // Merchant Account Information (Pix)
        $pixInfo = '0014br.gov.bcb.pix01' . $this->formatLength($this->pixKey) . $this->pixKey;
        $payload .= '26' . $this->formatLength($pixInfo);
        $payload .= $pixInfo;

        // Additional Data
        $additionalData = '05' . $this->formatLength($transactionId) . $transactionId;
        $payload .= '62' . $this->formatLength($additionalData);
        $payload .= $additionalData;

        // CRC16
        $payload .= '6304';
        $crc = $this->calculateCRC16($payload . '6304');
        $payload .= strtoupper(dechex($crc));

        return $payload;
    }

    public function generateQrCode(float $amount, string $transactionId, string $description = ''): string
    {
        $payload = $this->generatePayload($amount, $transactionId, $description);

        $options = new QROptions([
            'outputType' => QROutputInterface::GDIMAGE_PNG,
            'eccLevel' => QRCode::ECC_L,
            'scale' => 10,
            'imageBase64' => false,
            'bgColor' => [255, 255, 255],
            'drawLightModules' => false,
            'moduleValues' => [
                QRMatrix::M_DATA_DARK => [0, 0, 0],
                QRMatrix::M_FINDER_DARK => [0, 0, 0],
                QRMatrix::M_ALIGNMENT_DARK => [0, 0, 0],
                QRMatrix::M_TIMING_DARK => [0, 0, 0],
            ],
        ]);

        $qrcode = new QRCode($options);
        $imageData = $qrcode->render($payload);

        return $imageData;
    }

    private function formatLength(string $data): string
    {
        $length = strlen($data);
        return str_pad($length, 2, '0', STR_PAD_LEFT);
    }

    private function calculateCRC16(string $data): int
    {
        $polynomial = 0x1021;
        $crc = 0xFFFF;

        for ($i = 0; $i < strlen($data); $i++) {
            $crc ^= (ord($data[$i]) << 8);
            for ($j = 0; $j < 8; $j++) {
                if ($crc & 0x8000) {
                    $crc = (($crc << 1) ^ $polynomial) & 0xFFFF;
                } else {
                    $crc = ($crc << 1) & 0xFFFF;
                }
            }
        }

        return $crc & 0xFFFF;
    }
}