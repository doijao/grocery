<?php
declare(strict_types=1);

namespace App\Controller;

class ItemCollection
{
    public $items = array();
    
    protected $result = array();
    
    protected $error_message = "";

    protected $totalPrice = array();

    public function addItem($obj, $key): void
    {
        if ($obj != null && $key != null) {
            $this->items[$key][] = $obj;
        } else {
            $this->error_message = "Missing object or key";
        }
    }

    public function deleteItem($key): void
    {
        if ($this->keyExist($key)) {
            unset($this->items[$key]);
        } else {
            $this->error_message = "Failed to remove item with $key key";
        }
    }

    public function getItem($key): array
    {
        if ($this->keyExist($key)) {
            return $this->items[$key];
        } else {
            $this->error_message = "Failed to get item that has $key key";
        }
    }

    public function countItem($key): int
    {
        return count($this->items[$key]);
    }

    protected function keyExist($key): bool
    {
        $result = isset($this->items[$key]);

        if ($result == false) {
            $this->error_message = "Invalid key $this->items[$key]";
        }
        
        return $result;
    }

    /*
    private function calcTotalPrice(): void
    {
        $total = array();

        foreach ($this->items as $key => $item) {
            $price = array_sum(array_column($item, 'price'));
            $qty = count($item);

            if (isset($item[0]->weight)) {
                $price = ($item[0]->price * $item[0]->weight) * $qty;
            }
            
            $total[] = $price;

            if ($item[0]->saleType == 'mIsOnBuyTwoTakeOne') {
                $freeItem = $qty - ceil($qty / 3 * 2);
            }

            if ($item[0]->saleType == 'mIsOnBuyOneTakeOne') {
                $freeItem = $qty - ceil($qty / 2 * 1);
            }

            if (isset($item[0]->saleType)) {
                $price = ($freeItem * $item[0]->price) * -1;
                $total[] = $price;
            }
        }

        $this->totalPrice = $total;
    }

    public function getTotalPrice()
    {
        return number_format(array_sum($this->totalPrice), 2);
    }

    public function showReceipt(): string
    {
        $total = array();
        $result = '';
        
        foreach ($this->items as $key => $item) {
            $name = $item[0]->name;
            $price = array_sum(array_column($item, 'price'));
            $qty = count($item);
            $qty_listing = " x $qty ";

            if (isset($item[0]->weight)) {
                $name = $item[0]->name . ' ( ' . $item[0]->weight . ' g) ';
                $price = ($item[0]->price * $item[0]->weight) * $qty;
            }
            
            $total[] = $price;
            $result .= $this->formatItem($name, $qty_listing, $price);
            
            if ($item[0]->saleType == 'mIsOnBuyTwoTakeOne') {
                $name = ' -- buy 2 take 1 ';
                $freeItem = $qty - ceil($qty / 3 * 2);
            }

            if ($item[0]->saleType == 'mIsOnBuyOneTakeOne') {
                $name = ' -- buy 1 take 1  ';
                $freeItem = $qty - ceil($qty / 2 * 1);
            }

            if (isset($item[0]->saleType)) {
                $qty_listing = "  ";
                $price = ($freeItem * $item[0]->price) * -1;
                $total[] = $price;
                $result .= $this->formatItem($name, $qty_listing, $price);
            }
        }
       
        $result .= "\n---------------------\n";
        $result .= "\n\nTotalPrice : \t Php " . number_format(array_sum($total), 2);
        $result .= "\n---------------------\n";

        return $result;
    }

    private function formatItem($name, $qty_listing, $price): string
    {
        $result = "\n" . $name . "\t\t" . $qty_listing . "\t" . number_format($price, 2);
        return $result;
    }
    */
}
