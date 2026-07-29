<?php

declare(strict_types=1);

namespace OnlineBooking\Model;

final readonly class Service
{
    public function __construct(
        public int $id,
        public int $companyId,
        public string $name,
        public int $priceMinor,
        public int $durationMinutes,
    ) {
    }
}
