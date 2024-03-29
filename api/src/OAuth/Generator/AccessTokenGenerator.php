<?php

declare(strict_types=1);

namespace App\OAuth\Generator;

use App\OAuth\Entity\AccessToken;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;

final class AccessTokenGenerator
{
    private string $privateKeyPath;

    public function __construct(string $privateKeyPath)
    {
        $this->privateKeyPath = $privateKeyPath;
    }

    /**
     * @param ScopeEntityInterface[] $scopes
     */
    public function generate(ClientEntityInterface $client, array $scopes, Params $params): AccessToken
    {
        $token = new AccessToken($client, $scopes);

        $token->setIdentifier(bin2hex(random_bytes(40)));
        $token->setExpiryDateTime($params->expires);
        $token->setUserIdentifier($params->userId);
        $token->setUserRole($params->role);

        $token->setPrivateKey(new CryptKey($this->privateKeyPath, null, false));

        return $token;
    }
}
