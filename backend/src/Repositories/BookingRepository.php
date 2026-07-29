<?php

declare(strict_types=1);

namespace OnlineBooking\Repositories;

use OnlineBooking\Model\Booking;

final readonly class BookingRepository
{
    // Реализация нас не волнует

    public function nextId(): int
    {
    }

    public function findUnconfirmedForPhone(string $phone): ?Booking
    {
    }

    public function getById(int $id): Booking
    {
    }

    public function save(Booking $booking): void
    {
    }

    public function delete(int $id): void
    {
    }
}
