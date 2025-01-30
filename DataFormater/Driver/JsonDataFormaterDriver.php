<?php

declare(strict_types = 1);

namespace App\Http\Controllers\AresClient\DataFormater\Driver;

use JsonException;

class JsonDataFormaterDriver implements DataFormaterDriverInterface
{
    /**
     * @throws JsonException
     */
    public function getFormatedData(array $data): string
    {
        return json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
    }
}
