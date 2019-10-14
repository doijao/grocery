<?php
declare(strict_types=1);

namespace App\Controller;

class Cart extends ItemCollection
{
    private $maxStrLength = 0;
    /**
     * calculate total amount
     * php 123,456.78
     */
    public function getTotalAmount(): string
    {
        return "\nTotal\t:\tphp " . number_format(array_sum($this->totalPrice), 2) ."\n";
    }

    private function getProductName($key): string
    {   
        return ucfirst($this->items[$key][0]->name) . ' (php ' . $this->items[$key][0]->price. ')';
    }

    /**
     * Option to display newly added item
     *  iPhone 6 ( x 2 )
     */
    public function getProductEntry($key): string
    { 
        $info = "\n".str_repeat("*",  40);
        $info .= "\nName \t: \t" . $this->getProductName($key);
        $info .= "\nQty \t: \t" . $this->countItem($key);
        if ($this->getProductWeight($key) != '') {
            $info .= "\nWeight \t: \t". $this->getProductWeight($key) * .001 . ' kg';
        }
        $info .= "\nAmount\t:\tphp " . $this->getTotalPrice($key);
        $info .= "\nDisc\t:\t" . $this->getDiscounted($key);
        $info .= "\n".str_repeat("*",  40);
        $info .= $this->getTotalAmount();
        
        return $info;
    }

    /**
     * calculate item total amount
     * item amount x quantity
     */
    private function getTotalPrice($key, $calc = true) : float
    {
        $result = array();
        if ($this->getProductWeight($key) != '') {
            for ($i=0; $i < count($this->items[$key]); $i++) {
                $result[] = $this->items[$key][$i]->price * $this->items[$key][$i]->weight;
            }
        } else {
            $result = array_column($this->items[$key], 'price');
        }

        if($calc) {
            $this->totalPrice[] = array_sum($result);
        }
        
        return (float) number_format(array_sum($result), 2);
    }

    /**
     * calculate item quantity
     */
    private function getProductWeight($key): string
    {
        $result = '';

        if (!empty($this->items[$key][0]->weight))
            $result = (string)$this->items[$key][0]->weight;
        
        return $result;
    }

    /**
     * display Product entries
     */
    private function getDiscounted($key , $calc = true): string
    {
        $result = '';

        if ($this->items[$key][0]->saleType != '') {

            $promoName = preg_replace('/(?<!\ )[A-Z]/', ' $0', str_replace("mIs","",$this->items[$key][0]->saleType));
            
            switch($this->items[$key][0]->saleType) {
                case 'mIsOnBuyTwoTakeOne' :
                    $freeItem =  3 * 2;
                    break;
                case 'mIsOnBuyOneTakeOne' :
                    $freeItem =  2 * 1;
                    break;
            }

            $deduction = (($this->countItem($key) - ceil($this->countItem($key) / $freeItem)) * $this->items[$key][0]->price) * -1;
            
            if($calc)
                $this->totalPrice[] = $deduction;

            $result = number_format($deduction, 2) . ' (' . $promoName . ' )';
        }

        return $result;
        
    }

    /**
     * display Product entries
     */
    public function printReceipt(): string
    {
        $result = "\n".str_repeat("=",  40);;
        $maxStrLength = 15;

        foreach ($this->items as $key => $item) {
            //echo $key."\n";
            $result .= "\n" . ucfirst($this->items[$key][0]->name) . '' . str_repeat(" ",  $maxStrLength - strlen($item[0]->name)) . "\t (x " . count($item) . ")\t php " . $this->getTotalPrice($key, false);
            $result .= "\n" . str_repeat(" ",  $maxStrLength - strlen($item[0]->name)) . "\t\t    " . $this->getDiscounted($key, false);
        }
        $result .= "\n".str_repeat("=",  40);
        $result .=  str_repeat(" ",  $maxStrLength - strlen($item[0]->name)) . '' . $this->getTotalAmount();
     
        return $result;
    }    
}
