<?php

declare(strict_types=1);

namespace OnlineBooking\Model;

final readonly class Employee
{
    public function __construct(
        public int $id,
        public int $companyId,
        public string $name,
    ) {
    }
}
