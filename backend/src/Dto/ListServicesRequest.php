<?php

declare(strict_types=1);

namespace OnlineBooking\Dto;

final readonly class ListServicesRequest
{
    // Заполняется как-то, не важно как
    private function __construct(
        public int $companyId,
    ) {
    }
}
