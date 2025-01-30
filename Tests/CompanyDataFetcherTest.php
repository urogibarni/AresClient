<?php

declare(strict_types = 1);

namespace Ares\CompanyDataFetcher\Tests;

use Ares\CompanyDataFetcher\CompanyDataFetcher;
use Ares\CompanyDataFetcher\DataFormater\CompanyDataFormater;
use Ares\CompanyDataFetcher\Util\CzechIcoValidator;
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
