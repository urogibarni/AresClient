<?php

declare(strict_types = 1);

namespace Ares\CompanyDataFetcher\DataFormater\Driver;

use SimpleXMLElement;

class XMLDataFormaterDriver implements DataFormaterDriverInterface
{
    public function getFormatedData(array $data): string
    {
        $xml = new SimpleXMLElement('<root/>');

        foreach ($data as $qualifiedName => $value) {
            $xml->addChild($qualifiedName, (string)$value);
        }

        return $xml->asXML();
    }
}
