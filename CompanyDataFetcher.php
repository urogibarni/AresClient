<?php

declare(strict_types = 1);

namespace App\Http\Controllers\AresClient;

use App\Http\Controllers\AresClient\DataFormater\CompanyDataFormater;
use App\Http\Controllers\AresClient\DataObject\CompanyDTO;
use App\Http\Controllers\AresClient\Exception\InvalidIcoException;
use App\Http\Controllers\AresClient\Exception\JsonDecodeErrorException;
use App\Http\Controllers\AresClient\HttpConnect\Exception\HttpConnectException;
use App\Http\Controllers\AresClient\HttpConnect\HttpProvider;
use App\Http\Controllers\AresClient\Util\CzechIcoValidator;
use App\Http\Controllers\AresClient\Util\UrlUtil;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
use JsonException;
use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;
use stdClass;
use Throwable;

final class CompanyDataFetcher
{
    private CompanyDTO $company;

    public function __construct(
        private readonly HttpProvider $httpProvider,
        private readonly CzechIcoValidator $icoValidator,
        private readonly ?LoggerInterface $logger = null,
        private readonly ?CompanyDataFormater $companyDataFormater = null
    ) {
    }

    public function fetch(string $ico): void
    {
        $this->handleIco($ico);

        $uri = UrlUtil::getUrl($ico);
        try {
            $this->httpProvider->request('GET', $uri);
        } catch (GuzzleException $exception) {
            $this->log(sprintf('HttpRequest Error: %s', $exception->getMessage()), LogLevel::ERROR);

            throw new HttpConnectException(sprintf('Error loading data from location: %s', $uri));
        }

        try {
            $companyData = $this->getDecodedData();
        } catch (JsonException $exception) {
            $this->log(sprintf('Data encoding error: %s', $exception->getMessage()), LogLevel::ERROR);

            throw new JsonDecodeErrorException('An error occurred while processing the data.');
        }

        $this->fillData($companyData);
    }

    public function getCompany(): CompanyDTO
    {
        return $this->company;
    }

    public function getExportCompanyData(): string
    {
        if (null === $this->companyDataFormater) {
            throw new InvalidArgumentException(
                sprintf(
                    'To obtain formatted output, you need to define `CompanyDataFormater` in the class `%s`.',
                    self::class
                )
            );
        }

        return $this->companyDataFormater->formatCompanyData($this->company);
    }

    private function handleIco(string $ico): void
    {
        if (!$this->icoValidator->isValid($ico)) {
            $this->log(sprintf('Invalid ICO: %s', $ico), LogLevel::ERROR);

            throw new InvalidIcoException(sprintf('Invalid ICO: %s', $ico));
        }
    }

    /**
     * @throws JsonException
     */
    private function getDecodedData(): stdClass
    {
        return json_decode($this->httpProvider->getContent(), false, 512, JSON_THROW_ON_ERROR);
    }

    private function fillData(stdClass $companyData): void
    {
        $this->company = (new CompanyDTO())
            ->setIco($companyData->ico)
            ->setDic($companyData->dic ?? null)
            ->setCompanyName($companyData->obchodniJmeno ?? null)
            ->setStreet($companyData->sidlo->nazevCastiObce ?? null)
            ->setVillageName($companyData->sidlo->nazevObce ?? null)
            ->setStateName($companyData->sidlo->nazevStatu ?? null)
            ->setZip($companyData->sidlo->psc ?? null)
        ;
    }

    private function log(string $message, string $level): void
    {
        if (null === $this->logger) {
            return;
        }

        $this->logger->log($level, sprintf('|AresClient| %s', $message));
    }
}
