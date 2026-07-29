<?php

declare(strict_types=1);

namespace OnlineBooking\Repositories;

use OnlineBooking\Model\Company;
use OnlineBooking\Model\Employee;
use OnlineBooking\Model\Service;

final readonly class CatalogRepository
{
    // Реализация нас не волнует

    public function getCompanyById(int $companyId): Company
    {
    }

    /**
     * @return Service[]
     */
    public function getServicesById(int $companyId): array
    {
    }

    public function getServiceById(int $serviceId): Service
    {
    }

    public function getEmployeeById(int $employeeId): Employee
    {
    }

    /**
     * @return Employee[]
     */
    public function getEmployeesByService(int $serviceId): array
    {
    }
}
