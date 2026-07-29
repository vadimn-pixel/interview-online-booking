<?php

declare(strict_types=1);

namespace OnlineBooking\Model;

final readonly class Client
{
    public function __construct(
        public int $id,
        public int $companyId,
        public string $phone,
    ) {
    }
}
