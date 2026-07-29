<?php

declare(strict_types=1);

namespace OnlineBooking\Service;

final readonly class BookingOtpSender
{
    // Реализация нас не волнует

    public function sendNewCode(int $bookingId, string $phone): void
    {
    }

    public function invalidateOldAndSendNewCode(int $bookingId, string $phone): void
    {
    }

    public function consume(int $bookingId, string $code): bool
    {
    }
}
