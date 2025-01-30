<?php

declare(strict_types = 1);

namespace App\Http\Controllers\AresClient\Util;

class UrlUtil
{
    private const BASE_URL = 'https://ares.gov.cz/ekonomicke-subjekty-v-be/rest/ekonomicke-subjekty';

    public static function getUrl(string $ico): string
    {
        return sprintf(
            '%s/%s',
            self::BASE_URL,
            str_pad(trim($ico), 8, '0', STR_PAD_LEFT)
        );
    }
}
