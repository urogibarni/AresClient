<?php

declare(strict_types = 1);

namespace Ares\CompanyDataFetcher\DataFormater;

use Ares\CompanyDataFetcher\DataFormater\Driver\DataFormaterDriverInterface;
use Ares\CompanyDataFetcher\DataObject\CompanyDTO;

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
