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
}
