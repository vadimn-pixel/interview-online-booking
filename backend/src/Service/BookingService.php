<?php

declare(strict_types=1);

namespace OnlineBooking\Service;

use DateTimeImmutable;
use OnlineBooking\Repositories\BookingRepository;
use OnlineBooking\Repositories\CatalogRepository;
use OnlineBooking\Repositories\ClientRepository;
use OnlineBooking\Dto\CreateBookingHoldRequest;
use OnlineBooking\Model\Booking;

final readonly class BookingService
{
    private const DATE_TIME_FORMAT = 'Y-m-d H:i:s';
    private const HOLD_TTL_MINUTES = 15;

    public function __construct(
        private CatalogRepository $catalogRepository,
        private ClientRepository $clientRepository,
        private BookingRepository $bookingRepository,
        private BookingOtpSender $bookingOtpSender,
        private NotificationService $notificationService,
    ) {
    }

    public function createHold(CreateBookingHoldRequest $request): Booking
    {
        $company = $this->catalogRepository->getCompanyById($request->companyId);
        $service = $this->catalogRepository->getServiceById($request->serviceId);
        if ($service->companyId !== $company->id) {
            throw ApiException::serviceDoesNotBelongToCompany();
        }
        $employee = $this->catalogRepository->getEmployeeById($request->employeeId);

        $timeNow = new DateTimeImmutable('now');
        $startsAt = new DateTimeImmutable($request->startsAtCompanyTz);

        $pendingHold = $this->bookingRepository->findUnconfirmedForPhone($request->phone);

        if ($pendingHold !== null) {
            if (! $this->isSameSlot($pendingHold, $employee->id, $service->id, $startsAt)) {
                throw ApiException::phoneHasAnotherActiveHold($request->phone);
            }

            $this->bookingOtpSender->invalidateOldAndSendNewCode($pendingHold->id, $request->phone);

            return $pendingHold;
        }

        $client = $this->clientRepository->findOrCreateByPhone($company->id, $request->phone);

        $booking = new Booking(
            id: $this->bookingRepository->nextId(),
            companyId: $company->id,
            clientId: $client->id,
            employeeId: $employee->id,
            serviceId: $service->id,
            startsAtCompanyTz: $startsAt->format(self::DATE_TIME_FORMAT),
            endsAtCompanyTz: $startsAt->modify(sprintf('+%d minutes', $service->durationMinutes))->format(self::DATE_TIME_FORMAT),
            priceMinor: self::parsePrice($request->price),
            holdExpiresAt: $timeNow->modify(sprintf('+%d minutes', self::HOLD_TTL_MINUTES))->format(self::DATE_TIME_FORMAT),
            confirmedAt: null,
        );

        $this->bookingRepository->save($booking);

        $this->bookingOtpSender->sendNewCode($booking->id, $request->phone);

        return $booking;
    }

    public function confirm(int $bookingId, string $code): Booking
    {
        $booking = $this->bookingRepository->getById($bookingId);
        $timeNow = new DateTimeImmutable('now');

        if ($timeNow > $booking->holdExpiresAt()) {
            throw ApiException::holdExpired();
        }

        if ($timeNow > $booking->startsAtCompanyTz()) {
            throw ApiException::slotAlreadyStarted();
        }

        $this->notificationService->notifyAdminAboutBooking($booking);

        if (! $this->bookingOtpSender->validate($bookingId, $code)) {
            throw ApiException::invalidCode();
        }

        $booking->confirmedAt = $timeNow->format(self::DATE_TIME_FORMAT);
        $this->bookingRepository->save($booking);

        return $booking;
    }

    public function delete(int $bookingId): void
    {
        $this->bookingRepository->delete($bookingId);
    }

    private function isSameSlot(Booking $booking, int $employeeId, int $serviceId, DateTimeImmutable $startsAt): bool
    {
        // реализовано правильно
    }

    private static function parsePrice(string $price): int
    {
        // реализовано правильно
    }
}
