<?php

declare(strict_types = 1);

namespace App\Http\Controllers\AresClient\Tests\Util;

use App\Http\Controllers\AresClient\Util\UrlUtil;
use PHPUnit\Framework\TestCase;

class UrlUtilTest extends TestCase
{
    public function testGetUrl5length(): void
    {
        $ico = '12345';
        $testedUrl = 'https://ares.gov.cz/ekonomicke-subjekty-v-be/rest/ekonomicke-subjekty/00012345';

        $this->assertEquals($testedUrl, UrlUtil::getUrl($ico));
    }

    public function testGetUrl8length(): void
    {
        $ico = '12345678';
        $testedUrl = 'https://ares.gov.cz/ekonomicke-subjekty-v-be/rest/ekonomicke-subjekty/12345678';

        $this->assertEquals($testedUrl, UrlUtil::getUrl($ico));
    }

    public function testGetUrlWithSpaces(): void
    {
        $ico = '   1234  ';
        $testedUrl = 'https://ares.gov.cz/ekonomicke-subjekty-v-be/rest/ekonomicke-subjekty/00001234';

        $this->assertEquals($testedUrl, UrlUtil::getUrl($ico));
    }

    public function testGetUrlEmpty(): void
    {
        $ico = '';
        $testedUrl = 'https://ares.gov.cz/ekonomicke-subjekty-v-be/rest/ekonomicke-subjekty/00000000';

        $this->assertEquals($testedUrl, UrlUtil::getUrl($ico));
    }
}
