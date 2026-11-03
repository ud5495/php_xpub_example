<?php

use Illuminate\Support\Facades\Route;
use Ud5495\XPub\Laravel\Facades\XPub;
use Ud5495\XPub\Coin;
use Ud5495\XPub\Exception\XPubExceptionInterface;
use Ud5495\XPub\Scheme;

Route::get('/', function () {
    $bxpub = env('BTC_XPUB_KEY');
    $dxpub = env('DOGE_XPUB_KEY');
    $lxpub = env('LTC_XPUB_KEY');
    $bcxpub = env('BCASH_XPUB_KEY');
    $dashxpub = env('DASH_XPUB_KEY');
    $ethxpub = env('ETH_XPUB_KEY');
    $tronxpub = env('TRON_XPUB_KEY');
    $xrpxpub = env('XRP_XPUB_KEY');

    $xpubKeys = [
        'BTC_XPUB_KEY'   => $bxpub,
        'DOGE_XPUB_KEY'  => $dxpub,
        'LTC_XPUB_KEY'   => $lxpub,
        'BCASH_XPUB_KEY' => $bcxpub,
        'DASH_XPUB_KEY'  => $dashxpub,
        'ETH_XPUB_KEY'   => $ethxpub,
        'TRON_XPUB_KEY'  => $tronxpub,
        'XRP_XPUB_KEY'   => $xrpxpub,
    ];

    $missing = [];
    foreach ($xpubKeys as $key => $value) {
        if (blank($value)) {
            $missing[] = $key;
        }
    }

    if (!empty($missing)) {
        return view('welcome', [
            'error' => 'The following XPUB keys are not set in your .env file: ' . implode(', ', $missing),
        ]);
    }
   
    try {
        // BIP84 scheme produces native SegWit (bc1q) BTC addresses
        $bkey = XPub::fromString($bxpub, Scheme::BIP84);
        $dkey = XPub::fromString($dxpub);
        $lkey = XPub::fromString($lxpub);
        $bckey = XPub::fromString($bcxpub);
        $dashkey = XPub::fromString($dashxpub);
        $ethkey = XPub::fromString($ethxpub);
        $tronkey = XPub::fromString($tronxpub);
        $xrkey = XPub::fromString($xrpxpub);

        $btcAddresses = [];
        $dogeAddresses = [];
        $ltcAddresses = [];
        $bcashAddresses = [];
        $dashAddresses = [];
        $ethAddresses = [];
        $tronAddresses = [];
        $xrpAddresses = [];

        for ($i = 0; $i < 1; $i++) {
            $btcChild = XPub::derive($bkey, [0, $i]);
            $dogeChild = XPub::derive($dkey, [0, $i]);
            $ltcChild = XPub::derive($lkey, [0, $i]);
            $bcashChild = XPub::derive($bckey, [0, $i]);
            $dashChild = XPub::derive($dashkey, [0, $i]);
            $ethChild = XPub::derive($ethkey, [$i]);
            $tronChild = XPub::derive($tronkey, [$i]);
            $xrpChild = XPub::derive($xrkey, [$i]);

            $btcAddresses[] = XPub::toAddress($btcChild, Coin::BTC);
            $dogeAddresses[] = XPub::toAddress($dogeChild, Coin::DOGE);
            $ltcAddresses[] = XPub::toAddress($ltcChild, Coin::LTC);
            $bcashAddresses[] = XPub::toAddress($bcashChild, Coin::BCH);
            $dashAddresses[] = XPub::toAddress($dashChild, Coin::DASH);
            $ethAddresses[] = XPub::toAddress($ethChild, Coin::ETH);
            $tronAddresses[] = XPub::toAddress($tronChild, Coin::TRON);
            $xrpAddresses[] = XPub::toAddress($xrpChild, Coin::XRP);
        }

        return view('welcome', [
            'btcAddresses' => $btcAddresses,
            'dogeAddresses' => $dogeAddresses,
            'ltcAddresses' => $ltcAddresses,
            'bcashAddresses' => $bcashAddresses,
            'dashAddresses' => $dashAddresses,
            'ethAddresses' => $ethAddresses,
            'tronAddresses' => $tronAddresses,
            'xrpAddresses' => $xrpAddresses,
        ]);
    } catch (XPubExceptionInterface $e) {
        return view('welcome', [
            'error' => $e->getMessage(),
        ]);
    }
});
