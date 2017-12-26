<?php

namespace Cemal\Services;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class JWTService
{
	private $signer;

	public function __construct()
	{
        $this->signer = new Sha256();
    }

    /**
     * get builder object
     * @return Builder
     */
    public function getBuilder()
    {
    	$builder = new Builder();
    	$builder->setIssuer(url('/'))
    			->setAudience(url('/'))
    			->setIssuedAt(time());

    	$expireTime  = (int)config('app.auth_expire_time');
    	if ($expireTime){
    		$builder->setExpiration(time() + (60 * $expireTime));
    	}

    	return $builder;
    }

    /**
     * get token by passing new claims
     * @param  array  $claims
     * @return string 			token
     */
    public function getToken(array $claims)
    {
    	$builder = $this->getBuilder();
    	foreach ($claims as $claim => $value) {
    		$builder->set($claim, $value);
    	}
    	$builder->sign($this->signer, config('app.key'));
    	return $builder->getToken()->__toString();
    }
}