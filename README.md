<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Crypto Address Derivation — Laravel + Node.js using ud5495/php_xpub


This project generates cryptocurrency deposit addresses from **extended public keys (XPUBs)** and **BIP-39 mnemonics**. It consists of two parts:

1. **Laravel web UI** — derives addresses from XPUB keys stored in `.env` using [`ud5495/php_xpub`](https://packagist.org/packages/ud5495/php_xpub) (`composer require ud5495/php_xpub`).
2. **Node.js CLI scripts** (`/node/`) — derives addresses **and private keys** from BIP-39 seed phrases using HD wallet derivation (BIP-32 / BIP-44), including xpub round-trip verification.

## Supported Coins

| Coin | Symbol | PHP (XPUB → Address) | Node.js (Mnemonic → Address + Private Key) |
|------|--------|----------------------|--------------------------------------------|
| Bitcoin | BTC | ✅ `ud5495/php_xpub` | — |
| Dogecoin | DOGE | ✅ `ud5495/php_xpub` | — |
| Litecoin | LTC | ✅ `ud5495/php_xpub` | — |
| Bitcoin Cash | BCH | ✅ `ud5495/php_xpub` | — |
| Dash | DASH | ✅ `ud5495/php_xpub` | — |
| Ethereum | ETH | ✅ `ud5495/php_xpub` | ✅ `m/44'/60'/0'/0` |
| TRON | TRX | ✅ `ud5495/php_xpub` | ✅ `m/44'/195'/0'/0` |
| Ripple | XRP | ✅ `ud5495/php_xpub` | ✅ `m/44'/144'/0'/0` |

## How It Works

### Laravel Backend (PHP)
- `routes/web.php` reads XPUB keys from `.env` (e.g., `BTC_XPUB_KEY`, `ETH_XPUB_KEY`, `TRON_XPUB_KEY`, `XRP_XPUB_KEY`, etc.)
- Uses `Ud5495\XPub\Laravel\Facades\XPub` to derive child keys and convert them to addresses per coin (`Coin::BTC`, `Coin::ETH`, `Coin::TRON`, `Coin::XRP`, etc.)
- Renders `resources/views/welcome.blade.php` showing one derived address per supported coin
- If any required XPUB key is missing from `.env`, an error message is shown instead

### Node.js CLI Scripts (`/node/`)
Each script loads a BIP-39 mnemonic, creates an HD wallet, generates an extended public key (xpub), rebuilds a public-only wallet from that xpub, derives 10 child addresses (indices 0–9), and prints the address, the `addressfromxpub` (verified against the xpub), and the private key for each index.

- `ethxpub.js` — Ethereum, path `m/44'/60'/0'/0`, uses `ethers` (`keccak256`)
- `tronxpub.js` — TRON, path `m/44'/195'/0'/0`, uses `ethers` (`keccak256`, `sha256`) + `bs58` (Base58Check)
- `xrpxpub.js` — XRP, path `m/44'/144'/0'/0`, uses `ethers` (`sha256`, `ripemd160`) + `ripple-address-codec`

## Installation

### 1. Clone the repository

```bash
git clone <repository-url>
cd /home/devv/Documents/myproject
```

### 2. Install PHP dependencies (Composer)

```bash
composer install
```

The `composer.json` already includes `ud5495/php_xpub`:

```bash
composer require ud5495/php_xpub
```

### 3. Install Node.js dependencies (npm)

```bash
npm install
```

Key Node.js dependencies (already in `package.json`):
- `ethers` (`^6.17.0`)
- `bs58` (`^6.0.0`)
- `ripple-address-codec` (`^5.0.1`)

### 4. Configure environment

Copy the example environment file and fill in your XPUB keys:

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set your XPUB keys:

```env
BTC_XPUB_KEY=your_bitcoin_zpub  use EXODUS WALLET TO EXPORT zpub
DOGE_XPUB_KEY=your_dogecoin_xpub use EXODUS WALLET TO EXPORT xpub
LTC_XPUB_KEY=your_litecoin_xpub use EXODUS WALLET TO EXPORT xpub
BCASH_XPUB_KEY=your_bitcoincash_xpub use EXODUS WALLET TO EXPORT xpub
DASH_XPUB_KEY=your_dash_xpub use EXODUS WALLET TO EXPORT xpub
ETH_XPUB_KEY=your_ethereum_xpub USE /node/ethxpub.js
TRON_XPUB_KEY=your_tron_xpub USE /node/tronxpub.js
XRP_XPUB_KEY=your_xrp_xpub USE /node/xrpxpub.js
```

### 5. Run database migrations (optional)

```bash
php artisan migrate --force
```

## Usage

### Run the Laravel web UI

```bash
php artisan serve
```

Visit `http://localhost:8000` to see derived addresses for all configured coins.

### Run the Node.js scripts

From the `/node/` directory:

```bash
cd /home/devv/Documents/myproject/node
node ethxpub.js
node tronxpub.js
node xrpxpub.js
```

Each script prints 10 derived addresses with their matching `addressfromxpub` and private keys.

## Project Structure

```
/home/devv/Documents/myproject/
├── README.md
├── artisan
├── composer.json          # PHP dependencies (includes ud5495/php_xpub)
├── composer.lock
├── package.json           # Node.js dependencies (ethers, bs58, ripple-address-codec, etc.)
├── package-lock.json
├── routes/
│   └── web.php            # XPUB address derivation routes (Laravel)
├── resources/
│   └── views/
│       └── welcome.blade.php  # UI showing derived coin addresses
├── node/
│   ├── ethxpub.js         # Ethereum mnemonic → address + private key (10 indices)
│   ├── tronxpub.js        # TRON mnemonic → address + private key (10 indices)
│   └── xrpxpub.js         # XRP mnemonic → address + private key (10 indices)
├── vite.config.js
└── ...
```

## ⚠️ Security Notice

- **Never commit real XPUB keys, mnemonics, or private keys to version control.**
- The `/node/` scripts use placeholder mnemonics (`YOUR_NEW_SEED_PHRASE`) — replace them with securely generated BIP-39 phrases for real use.
- Load secrets from `.env` (Laravel) or environment variables (Node.js), never hardcode them.
- XPUB keys are public, but deriving addresses from them can reveal payment information — handle with care.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
