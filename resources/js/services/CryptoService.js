/**
 * CryptoService.js
 * Service for handling cryptographic operations including:
 * - Key generation, import/export
 * - Message encryption/decryption
 * - Key storage in IndexedDB
 */

class CryptoService {
  constructor() {
    this.dbName = 'PrivMsgEncryptionKeys';
    this.dbVersion = 1;
  }

  /**
   * Initialize the IndexedDB database for key storage
   */
  async initDatabase() {
    return new Promise((resolve, reject) => {
      const request = indexedDB.open(this.dbName, this.dbVersion);
      
      request.onupgradeneeded = (event) => {
        const db = event.target.result;
        
        // Create object store for key pairs if it doesn't exist
        if (!db.objectStoreNames.contains('keys')) {
          const store = db.createObjectStore('keys', { keyPath: 'username' });
          store.createIndex('username', 'username', { unique: true });
        }
      };
      
      request.onsuccess = (event) => {
        resolve(event.target.result);
      };
      
      request.onerror = (event) => {
        console.error('Error opening database:', event.target.error);
        reject(event.target.error);
      };
    });
  }

  /**
   * Generate a new RSA key pair for encryption
   */
  async generateKeyPair() {
    try {
      // Generate key pair
      const keyPair = await window.crypto.subtle.generateKey(
        {
          name: 'RSA-OAEP',
          modulusLength: 2048,
          publicExponent: new Uint8Array([0x01, 0x00, 0x01]), // 65537
          hash: 'SHA-256',
        },
        true, // extractable
        ['encrypt', 'decrypt'] // key usages
      );
      
      // Export public key for storage on the server
      const exportedPublicKey = await this.exportPublicKey(keyPair.publicKey);
      
      return {
        privateKey: keyPair.privateKey,
        publicKey: keyPair.publicKey,
        exportedPublicKey
      };
    } catch (error) {
      console.error('Error generating key pair:', error);
      throw error;
    }
  }

  /**
   * Export a public key to PEM format
   */
  async exportPublicKey(publicKey) {
    try {
      // Export the key to SPKI format
      const spki = await window.crypto.subtle.exportKey('spki', publicKey);
      
      // Convert to base64
      const base64 = this.arrayBufferToBase64(spki);
      
      // Format as PEM
      return `-----BEGIN PUBLIC KEY-----\n${this.formatPEM(base64)}\n-----END PUBLIC KEY-----`;
    } catch (error) {
      console.error('Error exporting public key:', error);
      throw error;
    }
  }

  /**
   * Export a private key to PEM format
   */
  async exportPrivateKey(privateKey) {
    try {
      // Export the key to PKCS8 format
      const pkcs8 = await window.crypto.subtle.exportKey('pkcs8', privateKey);
      
      // Convert to base64
      const base64 = this.arrayBufferToBase64(pkcs8);
      
      // Format as PEM
      return `-----BEGIN PRIVATE KEY-----\n${this.formatPEM(base64)}\n-----END PRIVATE KEY-----`;
    } catch (error) {
      console.error('Error exporting private key:', error);
      throw error;
    }
  }

  /**
   * Import a public key from PEM format
   */
  async importPublicKey(pem) {
    try {
      // Remove headers and parse base64
      const base64 = pem
        .replace('-----BEGIN PUBLIC KEY-----', '')
        .replace('-----END PUBLIC KEY-----', '')
        .replace(/\s/g, '');
      
      // Convert to ArrayBuffer
      const binaryString = window.atob(base64);
      const bytes = new Uint8Array(binaryString.length);
      for (let i = 0; i < binaryString.length; i++) {
        bytes[i] = binaryString.charCodeAt(i);
      }
      
      // Import the key
      return await window.crypto.subtle.importKey(
        'spki',
        bytes.buffer,
        {
          name: 'RSA-OAEP',
          hash: 'SHA-256',
        },
        true, // extractable
        ['encrypt'] // key usages
      );
    } catch (error) {
      console.error('Error importing public key:', error);
      throw error;
    }
  }

  /**
   * Import a private key from PEM format
   */
  async importPrivateKey(pem) {
    try {
      // Remove headers and parse base64
      const base64 = pem
        .replace('-----BEGIN PRIVATE KEY-----', '')
        .replace('-----END PRIVATE KEY-----', '')
        .replace(/\s/g, '');
      
      // Convert to ArrayBuffer
      const binaryString = window.atob(base64);
      const bytes = new Uint8Array(binaryString.length);
      for (let i = 0; i < binaryString.length; i++) {
        bytes[i] = binaryString.charCodeAt(i);
      }
      
      // Import the key
      return await window.crypto.subtle.importKey(
        'pkcs8',
        bytes.buffer,
        {
          name: 'RSA-OAEP',
          hash: 'SHA-256',
        },
        true, // extractable
        ['decrypt'] // key usages
      );
    } catch (error) {
      console.error('Error importing private key:', error);
      throw error;
    }
  }

  /**
   * Store a key pair in IndexedDB
   */
  async storeKeyPair(privateKey, publicKey, username) {
    try {
      // Export keys to PEM format
      const exportedPrivateKey = await this.exportPrivateKey(privateKey);
      const exportedPublicKey = await this.exportPublicKey(publicKey);
      
      // Initialize database
      const db = await this.initDatabase();
      
      // Store keys
      return new Promise((resolve, reject) => {
        const transaction = db.transaction(['keys'], 'readwrite');
        const store = transaction.objectStore('keys');
        
        const request = store.put({
          username,
          privateKey: exportedPrivateKey,
          publicKey: exportedPublicKey,
          dateCreated: new Date().toISOString()
        });
        
        request.onsuccess = () => resolve();
        request.onerror = (event) => {
          console.error('Error storing key pair:', event.target.error);
          reject(event.target.error);
        };
      });
    } catch (error) {
      console.error('Error storing key pair:', error);
      throw error;
    }
  }

  /**
   * Retrieve a key pair from IndexedDB
   */
  async retrieveKeyPair(username) {
    try {
      // Initialize database
      const db = await this.initDatabase();
      
      // Retrieve keys
      return new Promise((resolve, reject) => {
        const transaction = db.transaction(['keys'], 'readonly');
        const store = transaction.objectStore('keys');
        
        const request = store.get(username);
        
        request.onsuccess = async (event) => {
          const data = event.target.result;
          
          if (!data) {
            resolve(null);
            return;
          }
          
          try {
            // Import keys from PEM format
            const privateKey = await this.importPrivateKey(data.privateKey);
            const publicKey = await this.importPublicKey(data.publicKey);
            
            resolve({
              privateKey,
              publicKey,
              exportedPrivateKey: data.privateKey,
              exportedPublicKey: data.publicKey
            });
          } catch (error) {
            console.error('Error importing keys from storage:', error);
            reject(error);
          }
        };
        
        request.onerror = (event) => {
          console.error('Error retrieving key pair:', event.target.error);
          reject(event.target.error);
        };
      });
    } catch (error) {
      console.error('Error retrieving key pair:', error);
      throw error;
    }
  }

  /**
   * Encrypt a message with a public key and sign it with the private key
   */
  async encryptMessage(message, publicKey, privateKey) {
    try {
      // Convert message to ArrayBuffer for encryption
      const encoder = new TextEncoder();
      const messageData = encoder.encode(message);
      
      // Generate a random symmetric key and IV for hybrid encryption
      const symmetricKey = await window.crypto.subtle.generateKey(
        {
          name: 'AES-GCM',
          length: 256
        },
        true,
        ['encrypt', 'decrypt']
      );
      
      // Generate random IV
      const iv = window.crypto.getRandomValues(new Uint8Array(12));
      
      // Encrypt the message with the symmetric key
      const encryptedData = await window.crypto.subtle.encrypt(
        {
          name: 'AES-GCM',
          iv: iv
        },
        symmetricKey,
        messageData
      );
      
      // Export the symmetric key
      const exportedKey = await window.crypto.subtle.exportKey('raw', symmetricKey);
      
      // Encrypt the symmetric key with the recipient's public key
      const encryptedKey = await window.crypto.subtle.encrypt(
        {
          name: 'RSA-OAEP'
        },
        publicKey,
        exportedKey
      );
      
      // Sign the encrypted message for authentication
      // First, we need to import the private key with signing capability
      const signingKey = await this.importPrivateKeyForSigning(await this.exportPrivateKey(privateKey));
      
      // Create a signature of the encrypted message
      const signature = await window.crypto.subtle.sign(
        {
          name: 'RSASSA-PKCS1-v1_5'
        },
        signingKey,
        encryptedData
      );
      
      // Return all components needed for decryption and verification
      return {
        encryptedMessage: this.arrayBufferToBase64(encryptedData),
        encryptedKey: this.arrayBufferToBase64(encryptedKey),
        iv: this.arrayBufferToBase64(iv),
        signature: this.arrayBufferToBase64(signature)
      };
    } catch (error) {
      console.error('Error encrypting and signing message:', error);
      throw error;
    }
  }
  
  /**
   * Import a private key for signing operations
   */
  async importPrivateKeyForSigning(pem) {
    try {
      // Remove headers and parse base64
      const base64 = pem
        .replace('-----BEGIN PRIVATE KEY-----', '')
        .replace('-----END PRIVATE KEY-----', '')
        .replace(/\s/g, '');
      
      // Convert to ArrayBuffer
      const binaryString = window.atob(base64);
      const bytes = new Uint8Array(binaryString.length);
      for (let i = 0; i < binaryString.length; i++) {
        bytes[i] = binaryString.charCodeAt(i);
      }
      
      // Import the key with signing capability
      return await window.crypto.subtle.importKey(
        'pkcs8',
        bytes.buffer,
        {
          name: 'RSASSA-PKCS1-v1_5',
          hash: 'SHA-256',
        },
        false, // not extractable
        ['sign'] // key usage for signing
      );
    } catch (error) {
      console.error('Error importing private key for signing:', error);
      throw error;
    }
  }

  /**
   * Import a public key for signature verification
   */
  async importPublicKeyForVerification(pem) {
    try {
      // Remove headers and parse base64
      const base64 = pem
        .replace('-----BEGIN PUBLIC KEY-----', '')
        .replace('-----END PUBLIC KEY-----', '')
        .replace(/\s/g, '');
      
      // Convert to ArrayBuffer
      const binaryString = window.atob(base64);
      const bytes = new Uint8Array(binaryString.length);
      for (let i = 0; i < binaryString.length; i++) {
        bytes[i] = binaryString.charCodeAt(i);
      }
      
      // Import the key with verification capability
      return await window.crypto.subtle.importKey(
        'spki',
        bytes.buffer,
        {
          name: 'RSASSA-PKCS1-v1_5',
          hash: 'SHA-256',
        },
        false, // not extractable
        ['verify'] // key usage for verifying
      );
    } catch (error) {
      console.error('Error importing public key for verification:', error);
      throw error;
    }
  }
  
  /**
   * Verify a message signature
   * @param {string} signature - Base64 signature to verify
   * @param {string} encryptedMessage - Base64 encrypted message data that was signed
   * @param {string} senderPublicKeyPem - Sender's public key in PEM format
   * @returns {Promise<boolean>} - True if signature is valid, false otherwise
   */
  async verifySignature(signature, encryptedMessage, senderPublicKeyPem) {
    try {
      // Convert Base64 signature to ArrayBuffer
      const signatureData = this.base64ToArrayBuffer(signature);
      
      // Convert Base64 encrypted message to ArrayBuffer
      const encryptedData = this.base64ToArrayBuffer(encryptedMessage);
      
      // Import the sender's public key for verification
      const verificationKey = await this.importPublicKeyForVerification(senderPublicKeyPem);
      
      // Verify the signature
      const isValid = await window.crypto.subtle.verify(
        {
          name: 'RSASSA-PKCS1-v1_5'
        },
        verificationKey,
        signatureData,
        encryptedData
      );
      
      return isValid;
    } catch (error) {
      console.error('Error verifying signature:', error);
      return false;
    }
  }

  /**
   * Decrypt a message using hybrid encryption
   * @param {string} encryptedMessage - Base64 encrypted message content
   * @param {string} encryptedKey - Base64 encrypted symmetric key
   * @param {string} iv - Base64 initialization vector
   * @param {CryptoKey} privateKey - The recipient's private key
   * @returns {Promise<string>} - The decrypted message
   */
  async decryptMessage(encryptedMessage, encryptedKey, iv, privateKey) {
    try {
      // Convert Base64 strings to ArrayBuffers
      const encryptedData = this.base64ToArrayBuffer(encryptedMessage);
      const encryptedKeyData = this.base64ToArrayBuffer(encryptedKey);
      const ivData = this.base64ToArrayBuffer(iv);
      
      // Decrypt the symmetric key using the private key
      const keyData = await window.crypto.subtle.decrypt(
        {
          name: 'RSA-OAEP'
        },
        privateKey,
        encryptedKeyData
      );
      
      // Import the decrypted symmetric key
      const symmetricKey = await window.crypto.subtle.importKey(
        'raw',
        keyData,
        {
          name: 'AES-GCM',
          length: 256
        },
        false, // not extractable
        ['decrypt'] // key usage
      );
      
      // Decrypt the message using the symmetric key
      const decryptedData = await window.crypto.subtle.decrypt(
        {
          name: 'AES-GCM',
          iv: new Uint8Array(ivData)
        },
        symmetricKey,
        encryptedData
      );
      
      // Convert ArrayBuffer to string
      const decoder = new TextDecoder();
      return decoder.decode(decryptedData);
    } catch (error) {
      console.error('Error decrypting message:', error);
      throw error;
    }
  }

  /**
   * Helper method to convert ArrayBuffer to base64
   */
  arrayBufferToBase64(buffer) {
    const bytes = new Uint8Array(buffer);
    let binary = '';
    for (let i = 0; i < bytes.byteLength; i++) {
      binary += String.fromCharCode(bytes[i]);
    }
    return window.btoa(binary);
  }

  /**
   * Helper method to convert base64 to ArrayBuffer
   */
  base64ToArrayBuffer(base64) {
    const binaryString = window.atob(base64);
    const bytes = new Uint8Array(binaryString.length);
    for (let i = 0; i < binaryString.length; i++) {
      bytes[i] = binaryString.charCodeAt(i);
    }
    return bytes.buffer;
  }

  /**
   * Helper method to format PEM content with line breaks
   */
  formatPEM(base64) {
    let result = '';
    for (let i = 0; i < base64.length; i += 64) {
      result += base64.slice(i, i + 64) + '\n';
    }
    return result.trim();
  }

  /**
   * Delete all user data from IndexedDB
   * Used when a user deletes their account
   */
  async deleteUserData(username) {
    try {
      // Initialize database
      const db = await this.initDatabase();
      
      // Delete keys
      return new Promise((resolve, reject) => {
        const transaction = db.transaction(['keys'], 'readwrite');
        const store = transaction.objectStore('keys');
        
        const request = store.delete(username);
        
        request.onsuccess = () => {
          console.log('User data deleted from IndexedDB');
          resolve(true);
        };
        
        request.onerror = (event) => {
          console.error('Error deleting user data:', event.target.error);
          reject(event.target.error);
        };
      });
    } catch (error) {
      console.error('Error deleting user data:', error);
      throw error;
    }
  }
}

export default new CryptoService();