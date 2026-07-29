<?php

declare(strict_types=1);

namespace OnlineBooking\Dto;

use DateTimeImmutable;

final readonly class CreateBookingHoldRequest
{
    // Заполняется как-то, не важно как
    private function __construct(
        public int $companyId,
        public int $serviceId,
        public int $employeeId,
        public string $price,
        public string $startsAtCompanyTz,
        public string $phone,
    ) {
    }
}
