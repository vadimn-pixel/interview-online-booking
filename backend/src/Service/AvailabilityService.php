<?php

declare(strict_types=1);

namespace OnlineBooking\Service;

use DateTimeImmutable;
use OnlineBooking\Model\Employee;
use OnlineBooking\Model\Service;

final readonly class AvailabilityService
{
    // Реализация нас не волнует

    public function availableSlots(int $companyId, int $serviceId, int $employeeId, string $date): array
    {
    }
}
