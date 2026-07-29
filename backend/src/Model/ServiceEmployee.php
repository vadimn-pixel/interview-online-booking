<?php

declare(strict_types=1);

namespace OnlineBooking\Model;

final readonly class ServiceEmployee
{
    // связь many to many услуги : сотрудники
    public function __construct(
        public int $serviceId,
        public int $employeeId,
    ) {
    }
}
