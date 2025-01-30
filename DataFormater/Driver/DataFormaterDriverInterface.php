<?php

declare(strict_types = 1);

namespace Ares\CompanyDataFetcher\DataFormater\Driver;

interface DataFormaterDriverInterface
{
    public function getFormatedData(array $data): string;
}
