<?php

declare(strict_types = 1);

namespace Tests\DataObject;

use Ares\CompanyDataFetcher\DataObject\CompanyDTO;
use PHPUnit\Framework\TestCase;

class CompanyDTOTest extends TestCase
{
    public function testCompanyDTO(): void
    {
        $company = (new CompanyDTO())
            ->setIco('test-ico-number')
            ->setCompanyName('Test company')
            ->setVillageName(null)
            ->setZip(15956)
        ;

        $this->assertIsNotInt($company->getIco());
        $this->assertEquals('Test company', $company->getCompanyName());
        $this->assertNull($company->getVillageName());
        $this->assertIsInt($company->getZip());
    }
}
