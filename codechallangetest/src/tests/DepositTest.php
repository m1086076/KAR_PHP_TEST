<?php
use PHPUnit\Framework\TestCase;
require_once(dirname(__DIR__)."/Deposit.php"); 

class DepositTest extends TestCase
{
    /**
     * valid account number
     */
    public function testDepositAmountValid() 
    {

       $deposit = new Deposit();
       $this->assertIsInt(6086000100057336);
       $this->assertIsstring("Nafees");
       $this->assertIsInt(500);
       $this->assertIsstring("REVIEWING");
       $this->assertIsstring("NO");
       
       echo $deposit->depositAmount(6086000100057336, "Nafees", 500, "REVIEWING", "NO");
    }
    /**
     * Invalid account number
     */
    public function testDepositAmountInValid() 
    {

       $deposit = new Deposit();
       $this->assertIsInt(6086000100057336);
       $this->assertIsstring("Nafees");
       $this->assertIsInt(500);
       $this->assertIsstring("REVIEWING");
       $this->assertIsstring("NO");
       
       echo $deposit->depositAmount('', "Nafees", 500, "REVIEWING", "NO");
    }
    /**
     * Invalid Amount 
     */
    public function testDepositAmountInValidAmt() 
    {

       $deposit = new Deposit();
       $this->assertIsInt(6086000100057336);
       $this->assertIsstring("Nafees");
       $this->assertIsInt(-100);
       $this->assertIsstring("REVIEWING");
       $this->assertIsstring("NO");
       
       echo $deposit->depositAmount(6086000100057336, "Nafees", -100, "REVIEWING", "NO");
    }
   
}