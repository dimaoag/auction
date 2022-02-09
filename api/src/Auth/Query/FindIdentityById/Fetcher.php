<?php

declare(strict_types=1);

namespace App\Auth\Query\FindIdentityById;

use Doctrine\DBAL\Connection;

final class Fetcher
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function fetch(string $id): ?Identity
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(['id', 'role'])
            ->from('auth_users')
            ->where('id = ?')
            ->setParameter(0, $id)
            ->executeQuery();

        /** @var array{id: string, role: string}|false */
        $row = $stmt->fetchAssociative();

        if ($row === false) {
            return null;
        }

        return new Identity(
            id: $row['id'],
            role: $row['role']
        );
    }
}
