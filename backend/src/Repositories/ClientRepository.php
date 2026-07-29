<?php

declare(strict_types=1);

namespace OnlineBooking\Repositories;

use OnlineBooking\Model\Client;

final readonly class ClientRepository
{
    // Реализация нас не волнует

    public function findOrCreateByPhone(int $companyId, string $phone): Client
    {
    }
}
