<?php
declare(strict_types=1);

namespace App\Collection;

class Product
{
    public $type;

    public $name;

    public $price;

    public $weight;

    public $saleType;

    public function __construct($type, $name, $price, $weight, $saleType)
    {
        $this->type     =   $type;
        $this->name     =   $name;
        $this->price    =   $price;
        $this->weight   =   $weight;
        $this->saleType =   $saleType;
    }
}
