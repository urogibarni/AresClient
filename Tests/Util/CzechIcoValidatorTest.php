<?php

declare(strict_types = 1);

namespace Tests\Util;

use App\Http\Controllers\AresClient\Util\UrlUtil;
use Ares\CompanyDataFetcher\Util\CzechIcoValidator;
use PHPUnit\Framework\TestCase;

class CzechIcoValidatorTest extends TestCase
{
    public function testValidIco()
    {
        $icoValidator = new CzechIcoValidator();
        $ico = '1569651';

        $this->assertTrue($icoValidator->isValid($ico));
        $this->assertEquals('01569651', $ico);
    }

    public function testIcoWithLeadingZeros()
    {
        $icoValidator = new CzechIcoValidator();
        $ico = '01569651';

        $this->assertTrue($icoValidator->isValid($ico));
        $this->assertEquals('01569651', $ico);
    }

    public function testInvalidIcoTooShort()
    {
        $icoValidator = new CzechIcoValidator();
        $ico = '1234567';

        $this->assertFalse($icoValidator->isValid($ico));
    }

    public function testInvalidIcoTooLong()
    {
        $icoValidator = new CzechIcoValidator();
        $ico = '123456789';

        $this->assertFalse($icoValidator->isValid($ico));
    }

    public function testInvalidIcoWithNonNumericCharacter()
    {
        $icoValidator = new CzechIcoValidator();
        $ico = 'non-numeric';

        $this->assertFalse($icoValidator->isValid($ico));
    }

    public function testInvalidIcoWithIncorrectControlDigit()
    {
        $icoValidator = new CzechIcoValidator();
        $ico = '12345678';

        $this->assertFalse($icoValidator->isValid($ico));
    }

    public function testEmptyIco()
    {
        $icoValidator = new CzechIcoValidator();
        $ico = '';

        $this->assertFalse($icoValidator->isValid($ico));
    }

    public function testTrimWhitespace()
    {
        $icoValidator = new CzechIcoValidator();
        $ico = '   1569651   ';

        $this->assertTrue($icoValidator->isValid($ico));
        $this->assertEquals('01569651', $ico);
    }
}
