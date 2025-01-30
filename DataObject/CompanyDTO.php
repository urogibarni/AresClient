<?php

declare(strict_types = 1);

namespace Ares\CompanyDataFetcher\DataObject;

use JsonSerializable;

class CompanyDTO implements JsonSerializable
{
    private string $ico;
    private ?string $companyName = null;
    private ?string $dic = null;
    private ?string $stateName = null;
    private ?string $villageName = null;
    private ?string $street = null;
    private ?int $zip = null;

    public function getIco(): string
    {
        return $this->ico;
    }

    public function setIco(string $ico): self
    {
        $this->ico = $ico;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(?string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getDic(): ?string
    {
        return $this->dic;
    }

    public function setDic(?string $dic): self
    {
        $this->dic = $dic;

        return $this;
    }

    public function getStateName(): ?string
    {
        return $this->stateName;
    }

    public function setStateName(?string $stateName): self
    {
        $this->stateName = $stateName;

        return $this;
    }

    public function getVillageName(): ?string
    {
        return $this->villageName;
    }

    public function setVillageName(?string $villageName): self
    {
        $this->villageName = $villageName;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getZip(): ?int
    {
        return $this->zip;
    }

    public function setZip(?int $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'ico' => $this->ico,
            'companyName' => $this->companyName,
            'dic' => $this->dic,
            'stateName' => $this->stateName,
            'villageName' => $this->villageName,
            'street' => $this->street,
            'zip' => $this->zip,
        ];
    }
}
