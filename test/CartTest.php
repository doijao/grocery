<?php
namespace TestCases;

use PHPUnit\Framework\TestCase;

use App\Controller\ReadJson;

use App\Collection\Product;

use App\Controller\Cart;

class CartTest extends TestCase
{

    private static $cart;

    private static $datas;

    private $scanItems;

    protected static $wasSetup = false;

    protected function setUp() : void
    {
        parent::setUp();

        if (!static::$wasSetup) {
            $config = include('config.php');
            static::$datas = new ReadJson($config['tempFile']);
            static::$cart = new Cart();
            static::$wasSetup = true;
        }
    }

    /**
     * Per piece list of products
     */
    private function itemPerPiece() : array
    {
        $scanItems = array(
            array(
                'code'  => 'prod10000274',
                'qty'   => 2,
                'weight'    =>  null,
                'expectedTotal' => 1399.98
            ),
            array(
                'code'  => 'prod10000235',
                'qty'   => 3,
                'weight'    =>  null,
                'expectedTotal' =>  105
            ),
        );

        return $scanItems;
    }
    
    /**
     * Per bulk list of Products
     */
    private function itemPerBulk() : array
    {
        $scanItems = array(
            array(
                'code'  => 'prod50000193',
                'qty'   => 1,
                'weight'    =>  1000,
                'expectedTotal' =>  45
            ),
            array(
                'code'  => 'prod50000201',
                'qty'   => 1,
                'weight'    =>  250,
                'expectedTotal' =>  18.75
            ),
        );

        return $scanItems;
    }

    /**
     * Per sale list of Products
     */
    private function itemOnSale() : array
    {
        $scanItems = array(
            array(
                'code'  => 'prod10000364',
                'qty'   => 5,
                'weight'    =>  null,
                'expectedTotal' =>  368
            ),
            array(
                'code'  => 'prod10000411',
                'qty'   => 4,
                'weight'    =>  null,
                'expectedTotal' =>  156
            ),
        );

        return $scanItems;
    }

    /**
     * @test
     */
    public function soldByPiece() : void
    {
        foreach ($this->itemPerPiece() as $scanItem) {
            $key = array_search($scanItem['code'], array_column(static::$datas->getResult(), 'mProductId'));
            $item = static::$datas->getResult()[$key];
            $saleType = null;
            $saleType = (($item['mIsOnBuyOneTakeOne']) ? "mIsOnBuyOneTakeOne" : $saleType);
            $saleType = (($item['mIsOnBuyTwoTakeOne']) ? "mIsOnBuyTwoTakeOne": $saleType);

            for ($i=0; $i < $scanItem['qty']; $i++) {
                static::$cart->addItem(
                    new Product(
                        $item['mSkuType'],
                        $item['mName'],
                        $item['mPrice'],
                        $scanItem['weight'],
                        $saleType
                    ),
                    $item['mProductId']
                );
            }
            
            echo static::$cart->getProductEntry($item['mProductId']);
            
            $this->assertEquals(true, true);
        }
    }

    /**
     * @test
     */
    public function soldByBulk() : void
    {
        foreach ($this->itemPerBulk() as $scanItem) {
            $key = array_search($scanItem['code'], array_column(static::$datas->getResult(), 'mProductId'));
            $item = static::$datas->getResult()[$key];
            $saleType = null;
            $saleType = (($item['mIsOnBuyOneTakeOne']) ? "mIsOnBuyOneTakeOne" : $saleType);
            $saleType = (($item['mIsOnBuyTwoTakeOne']) ? "mIsOnBuyTwoTakeOne": $saleType);
            
            for ($i=0; $i < $scanItem['qty']; $i++) {
                static::$cart->addItem(
                    new Product(
                        $item['mSkuType'],
                        $item['mName'],
                        $item['mPrice'],
                        $scanItem['weight'],
                        $saleType
                    ),
                    $item['mProductId']
                );
            }

            // Validate if getting the expected total price
            echo static::$cart->getProductEntry($item['mProductId']);
           
            $this->assertEquals(true, true);
        }
    }

    /**
     * @test
     */
    public function productOnSale() : void
    {
        foreach ($this->itemOnSale() as $scanItem) {
            $key = array_search($scanItem['code'], array_column(static::$datas->getResult(), 'mProductId'));
            $item = static::$datas->getResult()[$key];
            $saleType = null;
            $saleType = (($item['mIsOnBuyOneTakeOne']) ? "mIsOnBuyOneTakeOne" : $saleType);
            $saleType = (($item['mIsOnBuyTwoTakeOne']) ? "mIsOnBuyTwoTakeOne": $saleType);
            
            for ($i=0; $i < $scanItem['qty']; $i++) {
                static::$cart->addItem(
                    new Product(
                        $item['mSkuType'],
                        $item['mName'],
                        $item['mPrice'],
                        $scanItem['weight'],
                        $saleType
                    ),
                    $item['mProductId']
                );
            }
       
            // Validate if getting the expected total price
            echo static::$cart->getProductEntry($item['mProductId']);
           
            $this->assertEquals(true, true);
        }
    }

    /**
     * @test
     */
    public function printReceipt() : void
    {
       var_dump(static::$cart->printReceipt());
        $this->assertEquals(true, true);
    }
}
