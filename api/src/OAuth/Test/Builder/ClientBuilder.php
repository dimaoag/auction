<?php

declare(strict_types=1);

namespace App\OAuth\Test\Builder;

use Ramsey\Uuid\Uuid;
use App\OAuth\Entity\Client;

final class ClientBuilder
{
    private string $identifier;
    private string $name;
    private string $redirectUri;

    public function __construct()
    {
        $this->identifier = Uuid::uuid4()->toString();
        $this->name = 'Client';
        $this->redirectUri = 'http://localhost:8080/auth';
    }

    public function build(): Client
    {
        return new Client(
            $this->identifier,
            $this->name,
            $this->redirectUri,
        );
    }
}
