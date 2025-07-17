<?php

namespace App\Helpers;

use App\Exceptions\JwtException;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;

class JwtHelper implements Auth
{
    private $secret;

    private $encrypt;

    private $aud;

    private $privateKey = <<<'EOD'
-----BEGIN RSA PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAL68Nj2qrf5nJzLJ
SA9yZAY75pQ98fXKNixxnGfptPltdfn6anG7v8dfqejGgJuYtH2ULleMYJTII8gy
JkStQpr/oOsvTJSHdCz4m/8b58mD/IoxIjD3EBjnJ0vNnWIfGF10FJqGVGnjdGCX
yglrjRLcP54CTp1d01PfW82fBUhZAgMBAAECgYBvYbuPEV/gA175ol8pXhSZK1vA
T9g/P2GZXJMIf4rGaayOWTTVy9z3UZ8IJvstYeRsvR6+02QHHkT/AIImcsNEfE3z
xv/yPTLEKPcww79Ezo5glw/NA5bv3GNbR+BzMjvVf/VNoK3MjGb6oKknl4ClLb/D
KneJUir4gZKTFvHxHQJBAOASbIECwfqmJn/A9dYcOiUk4sRxMJLR0wS0gkkC/vVe
iWZWtJIjEmQRR5Qg/l6jwuC1B2p0mLn6DSqpSp8du5MCQQDZ6b9ddyIRzFZULT2n
25H0ItNXr1XelMtB0hbsUej5gPIYkqRygDmrXVn+t/As/2HMwFDkDLJKwsavfGYb
E1fjAkEAwSkgwFZQQnLY3WjKhDjxJvQVSKMK7IZVEslJRwd+IqfMapx0LUZupDUB
L4EBxzQE2xEzw2GgBzK4Bv7JhUFsYQJBAMROlc0+SFveR5r3UobH/7j+MoPYeTPV
uTGncG0d8RHrvqyyViCsMMeWhj84Ns5ilhkc2wJGCcvdoZ1vJJCZoV8CQEiowxPG
BnRdsJwskJYIlyN6znNbFSAc0PEDslTftSmLANm7/RLl3j7bndDeXPXPty5TRruQ
+kT93iF6PQGt1wE=
-----END RSA PRIVATE KEY-----
EOD;

    private $publicKey = <<<'EOD'
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC+vDY9qq3+ZycyyUgPcmQGO+aU
PfH1yjYscZxn6bT5bXX5+mpxu7/HX6noxoCbmLR9lC5XjGCUyCPIMiZErUKa/6Dr
L0yUh3Qs+Jv/G+fJg/yKMSIw9xAY5ydLzZ1iHxhddBSahlRp43Rgl8oJa40S3D+e
Ak6dXdNT31vNnwVIWQIDAQAB
-----END PUBLIC KEY-----
EOD;


    public function __construct()
    {
        $this->secret = config('global.jwtKey');
        $this->encrypt = config('global.encryptionHash');
        $this->aud = '';
    }

    public function signIn(array $data): string
    {
        $token = [
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24 * 7),
            'aud' => $this->aud(),
            'sub' => $data[0],
            'identifier' => $data[1],
            'uid' => $data[2],
        ];

        return JWT::encode($token, $this->privateKey, $this->encrypt);
    }

    public function check(string $token): void
    {
        try {
            $decode = JWT::decode($token, new Key($this->publicKey, $this->encrypt));

            if ($decode->aud != $this->aud()) {
                throw new JwtException('Invalid token', 401);
            }
        } catch (Exception $e) {
            throw new JwtException('Invalid token', 401);
        }
    }

    public function getData(): string
    {
        $token = request()->header('authentication');

        return JWT::decode($token, new Key($this->publicKey, $this->encrypt))->uid;
    }

    private function aud(): string
    {
        $aud = @$_SERVER['HTTP_USER_AGENT'];
        //        Log::info($aud);
        return sha1($aud);
    }

    public function encode(array $data)
    {
        return JWT::encode($data, $this->privateKey, $this->encrypt);
    }
    public function decode($token)
    {
        return JWT::decode($token, new Key($this->publicKey, $this->encrypt));
    }
}
