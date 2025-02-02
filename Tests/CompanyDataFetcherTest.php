<?php

declare(strict_types = 1);

namespace Tests;

use Ares\CompanyDataFetcher\CompanyDataFetcher;
use Ares\CompanyDataFetcher\DataFormater\CompanyDataFormater;
use Ares\CompanyDataFetcher\DataFormater\Driver\JsonDataFormaterDriver;
use Ares\CompanyDataFetcher\DataFormater\Driver\XMLDataFormaterDriver;
use Ares\CompanyDataFetcher\DataObject\CompanyDTO;
use Ares\CompanyDataFetcher\Exception\InvalidIcoException;
use Ares\CompanyDataFetcher\Exception\JsonDecodeErrorException;
use Ares\CompanyDataFetcher\HttpConnect\HttpProvider;
use Ares\CompanyDataFetcher\Util\CzechIcoValidator;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class CompanyDataFetcherTest extends TestCase
{
    public function testSuccessfullyFetchedCompany(): void
    {
        $provider = $this->createMock(HttpProvider::class);
        $icoValidator = $this->createMock(CzechIcoValidator::class);
        $logger = $this->createMock(LoggerInterface::class);

        $logger
            ->expects($this->never())
            ->method('log')
        ;

        $icoValidator
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true)
        ;

        $provider
            ->expects($this->once())
            ->method('getContent')
            ->willReturn('{ "ico": "01569651", "dic": "CZ01569651" }')
        ;

        $fetcher = new CompanyDataFetcher(
            httpProvider: $provider,
            icoValidator: $icoValidator,
            logger: $logger,
            companyDataFormater: null
        );
        $fetcher->fetch('01569651');

        $company = $fetcher->getCompany();

        $this->assertInstanceOf(CompanyDTO::class, $company);
        $this->assertEquals('01569651', $company->getIco());
        $this->assertEquals('CZ01569651', $company->getDic());
    }

    public function testInvalidIco(): void
    {
        $provider = $this->createMock(HttpProvider::class);
        $icoValidator = $this->createMock(CzechIcoValidator::class);
        $logger = $this->createMock(LoggerInterface::class);

        $this->expectException(InvalidIcoException::class);

        $logger
            ->expects($this->once())
            ->method('log')
        ;

        $icoValidator
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(false)
        ;

        $provider
            ->expects($this->never())
            ->method('getContent')
        ;

        $fetcher = new CompanyDataFetcher(
            httpProvider: $provider,
            icoValidator: $icoValidator,
            logger: $logger,
            companyDataFormater: null
        );
        $fetcher->fetch('01569651');
    }

    public function testInvalidContent(): void
    {
        $provider = $this->createMock(HttpProvider::class);
        $icoValidator = $this->createMock(CzechIcoValidator::class);
        $logger = $this->createMock(LoggerInterface::class);

        $this->expectException(JsonDecodeErrorException::class);

        $logger
            ->expects($this->once())
            ->method('log')
        ;

        $icoValidator
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true)
        ;

        $provider
            ->expects($this->once())
            ->method('getContent')
            ->willReturn('non-valid-xml'
        );

        $fetcher = new CompanyDataFetcher(
            httpProvider: $provider,
            icoValidator: $icoValidator,
            logger: $logger,
            companyDataFormater: null
        );
        $fetcher->fetch('01569651');
    }

    public function testJsonDataFormatter(): void
    {
        $provider = $this->createMock(HttpProvider::class);
        $icoValidator = $this->createMock(CzechIcoValidator::class);
        $logger = $this->createMock(LoggerInterface::class);
        $dataFormater = $this->createMock(CompanyDataFormater::class);

        $icoValidator
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true)
        ;

        $provider
            ->expects($this->once())
            ->method('getContent')
            ->willReturn('{ "ico": "01569651", "dic": "CZ01569651" }')
        ;

        $dataFormater = new CompanyDataFormater(new JsonDataFormaterDriver());

        $fetcher = new CompanyDataFetcher(
            httpProvider: $provider,
            icoValidator: $icoValidator,
            logger: $logger,
            companyDataFormater: $dataFormater
        );
        $fetcher->fetch('01569651');

        $exportedCompanyData = $fetcher->getExportCompanyData();
        $decodedComapnyData = json_decode($exportedCompanyData, true);

        $this->assertJson($exportedCompanyData);
        $this->assertNotNull($decodedComapnyData, 'The JSON is invalid.');
        $this->assertArrayHasKey('ico', $decodedComapnyData);
        $this->assertArrayHasKey('companyName', $decodedComapnyData);
        $this->assertArrayHasKey('dic', $decodedComapnyData);
        $this->assertArrayHasKey('stateName', $decodedComapnyData);
        $this->assertArrayHasKey('villageName', $decodedComapnyData);
        $this->assertArrayHasKey('street', $decodedComapnyData);
        $this->assertArrayHasKey('zip', $decodedComapnyData);
    }

    public function testXmlDataFormatter(): void
    {
        $provider = $this->createMock(HttpProvider::class);
        $icoValidator = $this->createMock(CzechIcoValidator::class);
        $logger = $this->createMock(LoggerInterface::class);
        $dataFormater = $this->createMock(CompanyDataFormater::class);

        $icoValidator
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true)
        ;

        $provider
            ->expects($this->once())
            ->method('getContent')
            ->willReturn('{ "ico": "01569651", "dic": "CZ01569651" }')
        ;

        $dataFormater = new CompanyDataFormater(new XMLDataFormaterDriver());

        $fetcher = new CompanyDataFetcher(
            httpProvider: $provider,
            icoValidator: $icoValidator,
            logger: $logger,
            companyDataFormater: $dataFormater
        );
        $fetcher->fetch('01569651');

        $exportedCompanyData = $fetcher->getExportCompanyData();

        $decodedComapnyData = simplexml_load_string($exportedCompanyData);

        $this->assertNotFalse($decodedComapnyData, 'The XML is invalid.');
        $this->assertObjectHasProperty('ico', $decodedComapnyData);
        $this->assertObjectHasProperty('companyName', $decodedComapnyData);
        $this->assertObjectHasProperty('dic', $decodedComapnyData);
        $this->assertObjectHasProperty('stateName', $decodedComapnyData);
        $this->assertObjectHasProperty('villageName', $decodedComapnyData);
        $this->assertObjectHasProperty('street', $decodedComapnyData);
        $this->assertObjectHasProperty('zip', $decodedComapnyData);
    }
}
