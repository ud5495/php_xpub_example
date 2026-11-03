import {
    HDNodeWallet,
    Mnemonic,
    sha256,
    ripemd160,
    getBytes
} from "ethers";

import { encodeAccountID } from "ripple-address-codec";

// Use a NEW seed phrase
const mnemonic = "YOUR_NEW_SEED_PHRASE";


const derivationPath = "m/44'/144'/0'/0";

function publicKeyToXrpAddress(publicKey) {

    const hash1 = sha256(publicKey);

    const accountId = ripemd160(hash1);

    return encodeAccountID(getBytes(accountId));
}

try {

    const phrase = Mnemonic.fromPhrase(mnemonic);

    const wallet = HDNodeWallet.fromPhrase(
        phrase.phrase,
        "",
        derivationPath
    );

    const xrpXpub = wallet.neuter().extendedKey;
    const publicWallet = HDNodeWallet.fromExtendedKey(xrpXpub);
    console.log("Derivation Path:", derivationPath);
    console.log("XRP XPUB:", xrpXpub);

    console.log("\nGenerated XRP Addresses and Private Keys:");

    for (let i = 0; i < 10; i++) {

        const child = wallet.deriveChild(i);

         const child1 = publicWallet.deriveChild(i);

        const addressfromxpub = publicKeyToXrpAddress(child1.publicKey);

        const address = publicKeyToXrpAddress(
            child.publicKey
        );

        console.log(`\nIndex: ${i}`);
        console.log("Address:", address);
        console.log("Addressfromxpub:", addressfromxpub);
        console.log("Private Key:", child.privateKey);

    }

} catch (error) {

    console.error(
        "Error:",
        error.shortMessage || error.message
    );

}
