<?php

namespace Cemal\Services;

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Cemal\Exceptions\NotValidException;

class JWTService
{
    private $signer;

    public function __construct()
    {
        $this->signer = new Sha256();
    }

    /**
     * get builder object.
     * @return Builder
     */
    public function getBuilder()
    {
        $builder = new Builder();
        $builder->issuedBy(url('/'))
                ->issuedAt(time());

        /*
    	 *  TODO: should we validate expiration time via jwt playload?
        $expireTime  = (int)config('app.auth_expire_time');
    	if ($expireTime){
    		$builder->setExpiration(time() + (60 * $expireTime));
    	}
        **/

        return $builder;
    }

    /**
     * get token by passing new claims.
     * @param  array  $claims
     * @return string 			token
     */
    public function getToken(array $claims)
    {
        $builder = $this->getBuilder();
        foreach ($claims as $claim => $value) {
            $builder->with($claim, $value);
        }
        $builder->sign($this->signer, config('app.key'));

        return $builder->getToken()->__toString();
    }

    /**
     * Parse string token.
     * @param  string $token
     * @return \Lcobucci\JWT\Token
     */
    public function parseToken($token)
    {
        try {
            $token = (new Parser())->parse((string) $token);

            return $token;
        } catch (\RuntimeException $e) {
            throw new NotValidException('Token is not valid');
        }
    }

    /**
     * Get claim from string token.
     * @param  string $token
     * @param  string $claim
     * @return any
     */
    public function getClaim($token, $claim)
    {
        return $this->parseToken($token)->getClaim($claim);
    }
}
