<?php

declare(strict_types=1);

namespace OnlineBooking\Service;

use OnlineBooking\Model\Booking;

final readonly class NotificationService
{
    // Реализация нас не волнует

    public function notifyAdminAboutBooking(Booking $booking): void
    {
    }
}
