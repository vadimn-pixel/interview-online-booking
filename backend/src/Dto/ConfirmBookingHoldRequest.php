<?php

declare(strict_types=1);

namespace OnlineBooking\Dto;

final readonly class ConfirmBookingHoldRequest
{
    // Заполняется как-то, не важно как
    private function __construct(
        public int $bookingId,
        public string $code,
    ) {
    }
}
