<?php

declare(strict_types=1);

namespace OnlineBooking\Dto;

final readonly class ListEmployeesRequest
{
    // Заполняется как-то, не важно как
    private function __construct(
        public int $serviceId,
    ) {
    }
}
