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
}
