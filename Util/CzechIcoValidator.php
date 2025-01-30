<?php

declare(strict_types = 1);

namespace Ares\CompanyDataFetcher\Util;

class CzechIcoValidator
{
    public function isValid(string &$ico): bool
    {
        if ('' === trim($ico)) {
            return false;
        }

        $ico = str_pad(trim($ico), 8, '0', STR_PAD_LEFT);

        if (!preg_match('/^\d{8}$/', $ico)) {
            return false;
        }

        $weights = [8, 7, 6, 5, 4, 3, 2];
        $sum = 0;

        for ($i = 0; $i < 7; $i++) {
            $sum += (int)$ico[$i] * $weights[$i];
        }

        $remainder = $sum % 11;
        $controlDigit = 0 === $remainder ? 0 : (11 - $remainder);

        if (10 === $controlDigit) {
            $controlDigit = 0;
        }

        return (int)$ico[7] === $controlDigit;
    }
}
