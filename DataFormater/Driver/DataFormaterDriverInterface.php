<?php

declare(strict_types = 1);

namespace App\Http\Controllers\AresClient\DataFormater\Driver;

interface DataFormaterDriverInterface
{
    public function getFormatedData(array $data): string;
}
