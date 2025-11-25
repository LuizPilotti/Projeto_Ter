<?php
define('INCLUDE_PATH', '/var/www/public/');
define('HOST', 'mysql:3306');
define('DATABASE', 'testepilotti');
define('USER', 'root');
define('PASSWORD', 'Caio.cuadra1010@@');
session_start();

const CIPHER_V1 = 'aes-256-cbc';
const CIPHER_V2 = 'aes-256-gcm';

function appKey(): string
{
    $senha = getenv('APP_KEY') ?: 'senha123';
    return hash('sha256', $senha, true);
}

function encrypt_v2(string $conteudo): string
{
    $key  = appKey();
    $ivLen = openssl_cipher_iv_length(CIPHER_V2);
    $iv   = random_bytes($ivLen);

    $tag = '';
    $cipher = openssl_encrypt(
        $conteudo,
        CIPHER_V2,
        $key,
        OPENSSL_RAW_DATA,
        $iv,
        $tag
    );

    if ($cipher === false) {
        throw new RuntimeException('Falha ao criptografar (v2).');
    }
    $payload = $iv . $tag . $cipher;
    return 'v2:' . base64_encode($payload);
}

function decrypt_v2(string $conteudo): string
{

    $data = base64_decode($conteudo, true);
    if ($data === false) {
        return '';
    }

    $key   = appKey();
    $ivLen = openssl_cipher_iv_length(CIPHER_V2);

    if (strlen($data) <= $ivLen + 16) {
        return '';
    }

    $iv     = substr($data, 0,  $ivLen);          
    $tag    = substr($data, $ivLen, 16);          
    $cipher = substr($data, $ivLen + 16);         

    $plain = openssl_decrypt(
        $cipher,
        CIPHER_V2,
        $key,
        OPENSSL_RAW_DATA,
        $iv,
        $tag
    );

    return $plain === false ? '' : $plain;
}
function encrypt_v1(string $conteudo): string
{
    $key   = appKey();
    $ivLen = openssl_cipher_iv_length(CIPHER_V1); 
    $iv    = random_bytes($ivLen);

    $cipher = openssl_encrypt(
        $conteudo,
        CIPHER_V1,
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );

    if ($cipher === false) {
        throw new RuntimeException('Falha ao criptografar (v1).');
    }

    return base64_encode($iv . $cipher);
}

function decrypt_v1(string $conteudo): string
{
    if ($conteudo === '') return '';

    $key = appKey();

    $data = base64_decode($conteudo, true);
    if ($data === false) {
        return '';
    }

    $ivLen = openssl_cipher_iv_length(CIPHER_V1); 
    if (strlen($data) <= $ivLen) {
        return '';
    }

    $iv     = substr($data, 0, $ivLen);    
    $cipher = substr($data, $ivLen);       

    $plain = openssl_decrypt(
        $cipher,
        CIPHER_V1,
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );

    if ($plain !== false) {
        return $plain;
    }

    $cipherLegacy = base64_decode($cipher, true);
    if ($cipherLegacy !== false) {
        $plainLegacy = openssl_decrypt(
            $cipherLegacy,
            CIPHER_V1,
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
        if ($plainLegacy !== false) {
            return $plainLegacy;
        }
    }

    $plainAlt = openssl_decrypt(
        $cipher,
        CIPHER_V1,
        $key,
        0,
        $iv
    );

    return $plainAlt === false ? '' : $plainAlt;
}

function criptografar(string $conteudo): string
{
    return encrypt_v2($conteudo);
}

function descriptografar(string $conteudo): string
{
    if ($conteudo === '') {
        return '';
    }

    if (substr($conteudo, 0, 3) === 'v2:') {
        $base64 = substr($conteudo, 3); 
        return decrypt_v2($base64);
    }

    return decrypt_v1($conteudo);
}
