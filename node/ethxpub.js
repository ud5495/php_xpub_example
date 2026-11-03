import {
    HDNodeWallet,
    Mnemonic,
    SigningKey,
    keccak256
} from "ethers";

// Your BIP-39 mnemonic
const mnemonic = "YOUR_NEW_SEED_PHRASE";

// Ethereum derivation path
const derivationPath = "m/44'/60'/0'/0";

// Convert public key to Ethereum address
function publicKeyToEthAddress(publicKey) {

    const uncompressed = SigningKey.computePublicKey(
        publicKey,
        false
    );

    // Remove 04 prefix
    const pubKey = "0x" + uncompressed.slice(4);

    // Keccak-256 hash
    const hash = keccak256(pubKey);

    // Last 20 bytes = Ethereum address
    return "0x" + hash.slice(-40);
}

try {

    if (!mnemonic) {
        throw new Error("ETH_MNEMONIC environment variable is missing");
    }

    // Validate mnemonic
    const phrase = Mnemonic.fromPhrase(mnemonic);

    // Generate Ethereum HD wallet
    const wallet = HDNodeWallet.fromPhrase(
        phrase.phrase,
        "",
        derivationPath
    );

    // Generate extended public key
    const ethXpub = wallet.neuter().extendedKey;
    const publicWallet = HDNodeWallet.fromExtendedKey(ethXpub);

    console.log("Derivation Path:", derivationPath);
    console.log("ETH Account Address:", wallet.address);
    console.log("ETH XPUB:", ethXpub);

    console.log("\nGenerated ETH Addresses with Private Keys:");

    for (let i = 0; i < 10; i++) {

        // Derive child wallet
        const child = wallet.deriveChild(i);
        const child1 = publicWallet.deriveChild(i);
        const addressfromxpub = publicKeyToEthAddress(child1.publicKey);
        // Generate Ethereum address
        const address = publicKeyToEthAddress(
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