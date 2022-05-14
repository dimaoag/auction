<?php

declare(strict_types=1);

namespace App\OAuth\Entity;

use Webmozart\Assert\Assert;
use League\OAuth2\Server\Entities\Traits\ScopeTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

final class Scope implements ScopeEntityInterface
{
    use EntityTrait;
    use ScopeTrait;

    public function __construct(string $identifier)
    {
        Assert::notEmpty($identifier);

        $this->setIdentifier($identifier);
    }

    /**
     * @TODO Remove after release with fixed return type
     * @see https://github.com/thephpleague/oauth2-server/pull/1251
     */
    public function jsonSerialize(): mixed
    {
        return $this->getIdentifier();
    }
}
