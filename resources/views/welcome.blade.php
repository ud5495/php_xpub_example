<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XPub Addresses</title>
    <style>
        body {
            font-family: system-ui, sans-serif;
            max-width: 600px;
            margin: 40px auto;
            padding: 0 20px;
            line-height: 1.6;
        }

        h1 {
            color: #333;
        }

        .coin {
            margin-bottom: 30px;
        }

        .address {
            font-family: monospace;
            background: #f5f5f5;
            padding: 4px 8px;
            border-radius: 4px;
            word-break: break-all;
        }

        .error {
            color: #dc2626;
            background: #fef2f2;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin: 8px 0;
        }
    </style>
</head>

<body>
    <h1>Derived Addresses</h1>

    @if ($error ?? null)
    <div class="error">{{ $error }}</div>
    @endif

    @if ($btcAddresses ?? null)
    <div class="coin">
        <h2>Bitcoin (BTC)</h2>
        <ul>
            @foreach ($btcAddresses as $i => $address)
            <li><span class="address">{{ $address }}</span></li>
            @endforeach
        </ul>
    </div>
    @endif

    @if ($dogeAddresses ?? null)
    <div class="coin">
        <h2>Dogecoin (DOGE)</h2>
        <ul>
            @foreach ($dogeAddresses as $i => $address)
            <li><span class="address">{{ $address }}</span></li>
            @endforeach
        </ul>
    </div>
    @endif

    @if ($ltcAddresses ?? null)
    <div class="coin">
        <h2>Litecoin (LTC)</h2>
        <ul>
            @foreach ($ltcAddresses as $i => $address)
            <li><span class="address">{{ $address }}</span></li>
            @endforeach
        </ul>
    </div>
    @endif

    @if ($bcashAddresses ?? null)
    <div class="coin">
        <h2>Bitcoin Cash (BCH)</h2>
        <ul>
            @foreach ($bcashAddresses as $i => $address)
             <li> <span class="address">{{ str_replace('bitcoincash:', '', $address) }}</span></li>
            @endforeach
        </ul>
    </div>
    @endif

    @if ($dashAddresses ?? null)
    <div class="coin">
        <h2>Dash (DASH)</h2>
        <ul>
            @foreach ($dashAddresses as $i => $address)
            <li><span class="address">{{ $address }}</span></li>
            @endforeach
        </ul>
    </div>
    @endif
    
    @if ($ethAddresses ?? null)
    <div class="coin">
        <h2>Ethereum (ETH)</h2>
        <ul>
            @foreach ($ethAddresses as $i => $address)
            <li><span class="address">{{ $address }}</span></li>
            @endforeach
        </ul>
    </div>
    @endif     
    
    @if ($tronAddresses ?? null)
    <div class="coin">
        <h2>Tron (TRON)</h2>
        <ul>        
            @foreach ($tronAddresses as $i => $address)
            <li><span class="address">{{ $address }}</span></li>
            @endforeach
        </ul>
    </div>
    @endif

    @if ($xrpAddresses ?? null)
    <div class="coin">
        <h2>Ripple (XRP)</h2>
        <ul>
            @foreach ($xrpAddresses as $i => $address)
            <li><span class="address">{{ $address }}</span></li>
            @endforeach
        </ul>
    </div>
    @endif  

    @if (blank($btcAddresses ?? null) && blank($dogeAddresses ?? null) && blank($ltcAddresses ?? null) && blank($bcashAddresses ?? null) && blank($dashAddresses ?? null) && blank($ethAddresses ?? null) && blank($tronAddresses ?? null) && blank($xrpAddresses ?? null) && blank($error ?? null))
    <p>Set your <code>XPUB_KEY</code> in <code>.env</code> to generate addresses.</p>
    @endif
</body>

</html>