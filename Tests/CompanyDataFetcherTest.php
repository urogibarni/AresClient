<?php

declare(strict_types = 1);

namespace App\Http\Controllers\AresClient\Tests;

use App\Http\Controllers\AresClient\CompanyDataFetcher;
use App\Http\Controllers\AresClient\DataFormater\CompanyDataFormater;
use App\Http\Controllers\AresClient\Util\CzechIcoValidator;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class CompanyDataFetcherTest extends TestCase
{
    public function test1()
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage();

        new CompanyDataFetcher(
            null,
            $this->createMock(CzechIcoValidator::class),
            $this->createMock(CompanyDataFormater::class),
            $this->createMock(LoggerInterface::class)
        );
    }
}
