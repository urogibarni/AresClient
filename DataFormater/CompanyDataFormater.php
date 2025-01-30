<?php

declare(strict_types = 1);

namespace App\Http\Controllers\AresClient\DataFormater;

use App\Http\Controllers\AresClient\DataFormater\Driver\DataFormaterDriverInterface;
use App\Http\Controllers\AresClient\DataObject\CompanyDTO;

class CompanyDataFormater
{
    private DataFormaterDriverInterface $driver;

    public function __construct(DataFormaterDriverInterface $driver)
    {
        $this->driver = $driver;
    }

    public function formatCompanyData(CompanyDTO $companyDTO): string
    {
        return $this->driver->getFormatedData($companyDTO->jsonSerialize());
    }
}
