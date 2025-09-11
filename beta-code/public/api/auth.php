<?php
function base64UrlDecode($data) {
    $remainder = strlen($data) % 4;
    if ($remainder) $data .= str_repeat('=', 4 - $remainder);
    return base64_decode(strtr($data, '-_', '+/'));
}

function verifyJWT($jwt, $secret) {
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) return false;

    list($header64, $payload64, $signature64) = $parts;

    $signature = hash_hmac('sha256', "$header64.$payload64", $secret, true);
    $expectedSig = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

    if (!hash_equals($expectedSig, $signature64)) return false;

    $payload = json_decode(base64UrlDecode($payload64), true);
    if (!$payload || !isset($payload['exp']) || $payload['exp'] < time()) return false;

    return $payload;
}