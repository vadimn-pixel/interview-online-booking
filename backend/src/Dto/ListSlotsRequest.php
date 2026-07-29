<?php

declare(strict_types=1);

namespace OnlineBooking\Dto;

final readonly class ListSlotsRequest
{
    // Заполняется как-то, не важно как
    private function __construct(
        public int $companyId,
        public int $serviceId,
        public int $employeeId,
        public string $date,
    ) {
    }
}
