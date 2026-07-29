<?php

declare(strict_types=1);

namespace OnlineBooking\Model;

use DateTimeImmutable;

final class Booking
{
    public function __construct(
        public readonly int $id,
        public readonly int $companyId,
        public readonly int $clientId,
        public readonly int $employeeId,
        public readonly int $serviceId,
        public readonly string $startsAtCompanyTz,
        public readonly string $endsAtCompanyTz,
        public readonly int $priceMinor,
        public readonly string $holdExpiresAt,
        public ?string $confirmedAt,
    ) {
    }

    public function startsAtCompanyTz(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->startsAtCompanyTz);
    }

    public function endsAtCompanyTz(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->endsAtCompanyTz);
    }

    public function holdExpiresAt(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->holdExpiresAt);
    }
}
