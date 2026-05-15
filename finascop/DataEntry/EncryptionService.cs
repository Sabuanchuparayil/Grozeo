using System;
using System.IO;
using System.Security.Cryptography;
using System.Text;

namespace DataEntry
{
    public static class EncryptionService
    {
        public static string EncryptionKey { get; set; }

        private static string defaultKey = Environment.GetEnvironmentVariable("ENCRYPTION_KEY") ?? "SDS_Agent#eNC@Key";

        public static string CreateSaltKey(int size)
        {
            var buff = new byte[size];
            using (var rng = RandomNumberGenerator.Create())
            {
                rng.GetBytes(buff);
            }
            return Convert.ToBase64String(buff);
        }

        public static string CreatePasswordHash(string password, string saltkey, string passwordFormat = "SHA256")
        {
            return CreateHash(Encoding.UTF8.GetBytes(String.Concat(password, saltkey)), passwordFormat);
        }

        public static string CreateHash(byte[] data, string hashAlgorithm = "SHA256")
        {
            if (String.IsNullOrEmpty(hashAlgorithm))
                hashAlgorithm = "SHA256";

            using var algorithm = HashAlgorithm.Create(hashAlgorithm);
            if (algorithm == null)
                throw new ArgumentException("Unrecognized hash name");

            var hashByteArray = algorithm.ComputeHash(data);
            return BitConverter.ToString(hashByteArray).Replace("-", "");
        }

        public static string EncryptText(string plainText, string encryptionPrivateKey = "")
        {
            if (string.IsNullOrEmpty(plainText))
                return plainText;

            if (String.IsNullOrEmpty(encryptionPrivateKey))
                encryptionPrivateKey = String.IsNullOrEmpty(EncryptionKey) ? defaultKey : EncryptionKey;

            using var aes = Aes.Create();
            aes.Key = Encoding.ASCII.GetBytes(encryptionPrivateKey.Substring(0, 16));
            aes.IV = Encoding.ASCII.GetBytes(encryptionPrivateKey.Substring(8, 16));

            byte[] encryptedBinary = EncryptTextToMemory(plainText, aes.Key, aes.IV);
            return Convert.ToBase64String(encryptedBinary);
        }

        public static string DecryptText(string cipherText, string encryptionPrivateKey = "")
        {
            if (String.IsNullOrEmpty(cipherText))
                return cipherText;

            if (String.IsNullOrEmpty(encryptionPrivateKey))
                encryptionPrivateKey = String.IsNullOrEmpty(EncryptionKey) ? defaultKey : EncryptionKey;

            using var aes = Aes.Create();
            aes.Key = Encoding.ASCII.GetBytes(encryptionPrivateKey.Substring(0, 16));
            aes.IV = Encoding.ASCII.GetBytes(encryptionPrivateKey.Substring(8, 16));

            byte[] buffer = Convert.FromBase64String(cipherText);
            return DecryptTextFromMemory(buffer, aes.Key, aes.IV);
        }

        #region Utilities

        private static byte[] EncryptTextToMemory(string data, byte[] key, byte[] iv)
        {
            using (var ms = new MemoryStream())
            {
                using var aes = Aes.Create();
                using (var cs = new CryptoStream(ms, aes.CreateEncryptor(key, iv), CryptoStreamMode.Write))
                {
                    byte[] toEncrypt = Encoding.Unicode.GetBytes(data);
                    cs.Write(toEncrypt, 0, toEncrypt.Length);
                    cs.FlushFinalBlock();
                }
                return ms.ToArray();
            }
        }

        private static string DecryptTextFromMemory(byte[] data, byte[] key, byte[] iv)
        {
            using (var ms = new MemoryStream(data))
            {
                using var aes = Aes.Create();
                using (var cs = new CryptoStream(ms, aes.CreateDecryptor(key, iv), CryptoStreamMode.Read))
                {
                    using (var sr = new StreamReader(cs, Encoding.Unicode))
                    {
                        return sr.ReadLine();
                    }
                }
            }
        }

        #endregion
    }
}
