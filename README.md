# ARES-CompanyDataFetcher

###### 1. Introduction

This PHP library allows you to retrieve company data from the Czech Companies Register (ARES). 
It uses the official REST API endpoint and provides validated and structured data about economic entities based on their IČO (Registration Number).

###### 2. Functionality

- Loading company data from ARES based on the entered ID number.
- Input validation (checking the correctness of the ID number).
- Handling error conditions (e.g. non-existent ID number, unresponsive server).
- Returning data in a suitable format for further processing (JSON/array).
- Adherence to SOLID principles for good maintainability and extensibility of the code.

###### 3. Requirements

- PHP 8+
- cURL extension or other HTTP client method (e.g. Guzzle)

###### 4. Installation

If you are using Composer, you can add the library as a custom package or simply integrate it into your project:

```
composer require vasabalik/ares-client
```

Or manually copy the files into your project and call it with require.

###### 5. Usage

**Loading Company Data**

```php
<?php

use AresClient\CompanyDataFetcher;
use AresClient\HttpConnect\HttpProvider;
use GuzzleHttp\Client;
use AresClient\Util\CzechIcoValidator;
use Psr\Log\NullLogger;
    ...
        try {
        $fetcher = new CompanyDataFetcher(
            new HttpProvider(new Client() /* OR other comaptible HttpClient, OR: new FileGetContentsClient()*/),
            new CzechIcoValidator(),
            new NullLogger(), /* OR: null, OR: other copatible Logger */
            new CompanyDataFormater(new XMLDataFormater() /* OR: new JsonDataFormater() */) /* OR: null */
        );
        $fetcher->fetch('1569651');

        // Returns company data in a format suitable for further PHP processing.
        $fetcher->getCompany(); 
        // Returns company formated data based on the defined `CompanyDataFormater` driver, (for example, for export purposes).
        $fetcher->getExportCompanyData(); 

    } catch (Exception $exception) {
        echo 'Error: ' . $exception->getMessage();
    }
...    
```

**Example Output**

```php
AresClient\DataObject\CompanyDTO {
  -ico: "01569651"
  -companyName: "Superfaktura.cz, s.r.o."
  -dic: "CZ01569651"
  -stateName: "Česká republika"
  -villageName: "Brno"
  -street: "Židenice"
  -zip: 61500
}
```

**Example Json Export**

```json
{ 
    "ico": "01569651", 
    "companyName": "Superfaktura.cz, s.r.o.", 
    "dic": "CZ01569651", 
    "stateName": "Česká republika", 
    "villageName": "Brno", 
    "street": "Židenice", 
    "zip": 61500 
}
```

**Example Json Export**

```xml
<?xml version="1.0"?>
<root>
    <ico>01569651</ico>
    <companyName>Superfaktura.cz, s.r.o.</companyName>
    <dic>CZ01569651</dic>
    <stateName>Česká republika</stateName>
    <villageName>Brno</villageName>
    <street>Židenice</street>
    <zip>61500</zip>
</root>
```


###### 6. Error Handling

The library handles the following errors:

- **Invalid ICO**: If the ICO does not meet the valid Czech business registration criteria (e.g., not having the correct format or failing validation checks), it is considered invalid.

- **Nonexistent ICO**: If no company with the given ICO exists in the registry, this is considered an error.

- **API Error**: If the ARES API does not respond or returns invalid data, an error is triggered.

###### 7. Testing

The library includes a test suite. Tests can be run using the following command:

```
phpunit Tests/ --testdox
```

The tests cover input validation, response processing, and error scenarios.


### Test result examples:

**Company Data Fetcher (Tests\CompanyDataFetcher)**
```
✔ Successfully fetched company
✔ Invalid ico
✔ Invalid content
✔ Json data formatter
✔ Xml data formatter
```

**Company DTO (Tests\DataObject\CompanyDTO)**
```
✔ Company d t o
```

**Http Provider (Tests\HttpConnect\HttpProvider)**
```
✔ Success response
```

**Czech Ico Validator (Tests\Util\CzechIcoValidator)**
```
✔ Valid ico
✔ Ico with leading zeros
✔ Invalid ico too short
✔ Invalid ico too long
✔ Invalid ico with non numeric character
✔ Invalid ico with incorrect control digit
✔ Empty ico
✔ Trim whitespace
```

**Url Util (Tests\Util\UrlUtil)**
```
✔ Get url 5length
✔ Get url 8length
✔ Get url with spaces
✔ Get url empty
```
