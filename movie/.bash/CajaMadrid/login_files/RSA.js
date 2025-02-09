function encryptedString(key, s)
{
	if (key.chunkSize > key.digitSize - 11 || s.length>key.chunkSize){
	    return "Error";
	}
	var a = new Array();
	var sl = s.length;
	var i = 0;
	while (i < sl) {
		a[i] = s.charCodeAt(i);
		i++;
	}
	
	var k, block;
	block = new BigInt();
	i = 0; //bloque 0
	var b = new Array();
	var x;
	for (x=0; x<sl; x++){
		b[x] = a[i+sl-1-x];
	}
	b[sl] = 0;
	var paddedSize = Math.max(8, key.digitSize - 3 - sl);
	for (x=0; x<paddedSize; x++) {
		b[sl+1+x] = Math.floor(Math.random()*254) + 1; // [1,255]
	}
	b[key.digitSize-2] = 2; // marker
	b[key.digitSize-1] = 0; // marker
	var j = 0; 
	for (k = 0; k < key.digitSize; ++j){
	    block.digits[j] = b[k++];
	    block.digits[j] += b[k++] << 8;
	}
	var crypt = key.barrett.powMod(block, key.e);
	var result = key.radix == 16 ? biToHex(crypt) : biToString(crypt, key.radix);
	return result;
}

function RSAKeyPair(encryptionExponent, decryptionExponent, modulus){
	this.e = biFromHex(encryptionExponent);
	this.d = biFromHex(decryptionExponent);
	this.m = biFromHex(modulus);
	this.digitSize = 2 * biHighIndex(this.m) + 2;
	this.chunkSize = this.digitSize - 11; // maximum, anything lower is fine
	this.radix = 16; //Base.
	this.barrett = new BarrettMu(this.m);
}