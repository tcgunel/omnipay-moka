<?php

namespace Omnipay\Moka\Constants;

class ErrorCode
{
    public const CODES = [
        '000' => 'Genel Hata',
        '001' => 'Dealer bulunamadi',
        '002' => 'CheckKey hatali',
        '003' => 'Dealer kilitli',
        '004' => 'IP hatasi',
        '005' => 'Kart numarasi hatali',
        '006' => 'Son kullanma tarihi hatali',
        '007' => 'CVC hatali',
        '008' => 'Tutar hatali',
        '009' => 'Para birimi hatali',
        '010' => 'Taksit sayisi hatali',
        '011' => 'OtherTrxCode bos olamaz',
        '012' => 'ClientIP bos olamaz',
        '013' => 'RedirectUrl bos olamaz',
        '014' => 'Kart sahibi adi bos olamaz',
        '015' => 'Bu islem daha once yapilmis',
        '016' => 'BuyerInfo bilgileri eksik',
        '017' => 'BasketInfo bilgileri eksik',
        '018' => '3D dogrulama basarisiz',
        '019' => 'VirtualPosOrderId hatali',
        '020' => 'Islem bulunamadi',
        '021' => 'Islem daha once iptal edilmis',
        '022' => 'Islem tutari iade tutarindan kucuk',
        '023' => 'BinNumber bos olamaz',
        '024' => 'BinNumber en az 6 hane olmali',
        '025' => 'Islem bulunamadi (iade)',
        '026' => 'Iade islemi basarisiz',
        '027' => 'Iptal islemi basarisiz',
    ];

    public static function getMessage(string $code): string
    {
        return self::CODES[$code] ?? "Bilinmeyen hata kodu: {$code}";
    }
}
