<?php
namespace App\Core;

class JWT {
    private static function secret(): string {
        return Env::get('JWT_SECRET');
    }

    private static function base64UrlEncode(string $data): string {
        return rtrim(strtr(base64_encode($data), "+/", "-_"), "=");
    }

    private static function base64UrlDecode(string $data): string {
        return base64_decode(strtr($data, "-_", "+/"));
    }

    public static function generate(array $payload, int $exp = 3600): string {
        $header = self::base64UrlEncode(json_encode([
            "alg" => "HS256",
            "typ" => "JWT"
        ]));

        $payload['exp'] = time() + $exp;
        $payload = self::base64UrlEncode(json_encode($payload));

        $signature = hash_hmac(
            "sha256",
            "$header.$payload",
            self::secret(),
            true
        );

        $signature = self::base64UrlEncode($signature);

        return "$header.$payload.$signature";
    }

    public static function validate(string $token): ?array {
        $parts = explode('.', $token);

        if (count($parts) !== 3) return null;

        [$header, $payload, $signature] = $parts;

        $validSignature = self::base64UrlEncode(
            hash_hmac(
                "sha256",
                "$header.$payload",
                self::secret(),
                true
            )
        );
        if (!hash_equals($validSignature, $signature)) return null;

        $decoded = json_decode(self::base64UrlDecode($payload), true);
        if ($decoded['exp'] < time()) return null;

        return $decoded;
    }
}
?>