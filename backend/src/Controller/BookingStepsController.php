<?php

declare(strict_types=1);

namespace OnlineBooking\Controller;

use NumberFormatter;
use OnlineBooking\Repositories\CatalogRepository;
use OnlineBooking\Dto\ConfirmBookingHoldRequest;
use OnlineBooking\Dto\CreateBookingHoldRequest;
use OnlineBooking\Dto\DeleteBookingHoldRequest;
use OnlineBooking\Dto\ListEmployeesRequest;
use OnlineBooking\Dto\ListServicesRequest;
use OnlineBooking\Dto\ListSlotsRequest;
use OnlineBooking\Model\Booking;
use OnlineBooking\Model\Employee;
use OnlineBooking\Model\Service;
use OnlineBooking\Service\AvailabilityService;
use OnlineBooking\Service\BookingService;

final readonly class BookingStepsController
{
    private const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    public function __construct(
        private CatalogRepository $catalogRepository, // реализация нас не волнует
        private AvailabilityService $availabilityService, // реализация нас не волнует
        private BookingService $bookingService,
    ) {
    }

    #[Route('GET', '/steps/services')]
    public function services(ListServicesRequest $request): JsonResponse
    {
        $company = $this->catalogRepository->getCompanyById($request->companyId);
        $companyServices = $this->catalogRepository->getServicesById($company->id);

        $servicesArray = [];
        foreach ($companyServices as $service) {
            $employeesByService = $this->catalogRepository->getEmployeesByService($service->id);

            $servicesArray[] = [
                'id' => $service->id,
                'name' => $service->name,
                'price' => self::formatPrice($service->priceMinor, $company->currencyIso),
                'duration_formatted' => self::formatDuration($service->durationMinutes),
                'employees_count' => count($employeesByService),
            ];
        }

        return JsonResponse::ok(['services' => $servicesArray]);
    }

    #[Route('GET', '/steps/employees')]
    public function employees(ListEmployeesRequest $request): JsonResponse
    {
        $service = $this->catalogRepository->getServiceById($request->serviceId);
        $employeesByService = $this->catalogRepository->getEmployeesByService($service->id);

        return JsonResponse::ok([
            'employees' => array_map(
                static fn (Employee $employee): array => ['id' => $employee->id, 'name' => $employee->name],
                $employeesByService,
            ),
        ]);
    }

    #[Route('GET', '/steps/date-time')]
    public function dateTime(ListSlotsRequest $request): JsonResponse
    {
        return JsonResponse::ok([
            'date' => $request->date,
            'slots' => $this->availabilityService->availableSlots(
                companyId: $request->companyId,
                serviceId: $request->serviceId,
                employeeId: $request->employeeId,
                date: $request->date,
            ),
        ]);
    }

    #[Route('POST', '/steps/create-hold-and-get-code')]
    public function createHoldAndGetCode(CreateBookingHoldRequest $request): JsonResponse
    {
        $booking = $this->bookingService->createHold($request);

        return JsonResponse::created(self::bookingToArray($booking));
    }

    #[Route('POST', '/steps/confirm-code')]
    public function confirmCode(ConfirmBookingHoldRequest $request): JsonResponse
    {
        $booking = $this->bookingService->confirm($request->bookingId, $request->code);

        return JsonResponse::ok(self::bookingToArray($booking));
    }

    #[Route('DELETE', '/steps/delete-expired-hold')]
    public function deleteExpiredHold(DeleteBookingHoldRequest $request): JsonResponse
    {
        $this->bookingService->delete($request->bookingId);

        return JsonResponse::noContent();
    }

    private static function bookingToArray(Booking $booking): array
    {
        return [
            'booking_id' => $booking->id,
            'starts_at_company_tz' => $booking->startsAtCompanyTz()->format(self::DATE_TIME_FORMAT),
            'ends_at_company_tz' => $booking->endsAtCompanyTz()->format(self::DATE_TIME_FORMAT),
            'hold_expires_at_utc' => $booking->holdExpiresAt()->format(self::DATE_TIME_FORMAT),
        ];
    }

    private static function formatPrice(int $priceMinor, string $currencyIso): string
    {
        // какое-то форматирование (не важно)
    }


    private static function formatDuration(int $durationMinutes): string
    {
        // какое-то форматирование (не важно)
    }
}
