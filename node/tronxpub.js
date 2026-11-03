import {
    HDNodeWallet,
    Mnemonic,
    SigningKey,
    keccak256,
    sha256
} from "ethers";

import bs58 from "bs58";

// Use a NEW seed phrase.
const mnemonic = "YOUR_NEW_SEED_PHRASE";

// TRON derivation path
const derivationPath = "m/44'/195'/0'/0";

// Convert public key to TRON address
function publicKeyToTronAddress(publicKey) {

    const uncompressed = SigningKey.computePublicKey(
        publicKey,
        false
    );

    // Remove 04 prefix
    const pubKey = "0x" + uncompressed.slice(4);

    // Keccak-256 hash
    const hash = keccak256(pubKey);

    // Last 20 bytes + TRON network prefix
    const addressHex = "41" + hash.slice(-40);

    const payload = Buffer.from(addressHex, "hex");

    // Double SHA256 checksum
    const hash1 = sha256("0x" + payload.toString("hex"));
    const hash2 = sha256(hash1);

    const checksum = Buffer.from(hash2.slice(2, 10), "hex");

    // Base58Check encoding
    return bs58.encode(
        Buffer.concat([payload, checksum])
    );
}

try {

    if (!mnemonic) {
        throw new Error("TRON_MNEMONIC environment variable is missing");
    }

    // Validate mnemonic
    const phrase = Mnemonic.fromPhrase(mnemonic);

    // Generate TRON HD wallet
    const wallet = HDNodeWallet.fromPhrase(
        phrase.phrase,
        "",
        derivationPath
    );

    // Generate extended public key
    const tronXpub = wallet.neuter().extendedKey;
    const publicWallet = HDNodeWallet.fromExtendedKey(tronXpub);

    console.log("Derivation Path:", derivationPath);
    console.log("TRON XPUB:", tronXpub);

    console.log("\nGenerated TRON Addresses with Private Keys:");

    for (let i = 0; i < 10; i++) {

        // Derive child wallet
        const child = wallet.deriveChild(i);
        const child1 = publicWallet.deriveChild(i);
        const addressfromxpub = publicKeyToTronAddress(child1.publicKey);
        // Generate TRON address
        const address = publicKeyToTronAddress(
            child.publicKey
        );

        console.log("-----------------------------------");
        console.log(`Address ${i}:`, address);
         console.log("Addressfromxpub:", addressfromxpub);
        console.log(`Private Key ${i}:`, child.privateKey);
        console.log("-----------------------------------\n");

    }

} catch (error) {

    console.error(
        "Error:",
        error.shortMessage || error.message
    );

}