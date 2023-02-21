<?php
use PHPUnit\Framework\TestCase;
require_once(dirname(__DIR__)."/Withdrawal.php"); 

class WithdrawalTest extends TestCase
{
    /**
     * valid account number
     */
    public function testDepositAmountValid() 
    {

       $Withdrawal = new Withdrawal();
       $this->assertIsInt(6086000100057336);
       $this->assertIsstring("Nafees");
       $this->assertIsInt(1000);
       $this->assertIsInt(200);
       $this->assertIsstring("REVIEWING");
       $this->assertIsstring("NO");
       
       echo $Withdrawal->withdrawAmount(6086000100057336, "Nafees",1000,200, "REVIEWING", "NO");
    }
    /**
     * Invalid account number
     */
    public function testwithdrawAmount() 
    {

        $Withdrawal = new Withdrawal();
        $this->assertIsInt(6086000100057336);
        $this->assertIsstring("Nafees");
        $this->assertIsInt(10000);
        $this->assertIsInt(200);
        $this->assertIsstring("REVIEWING");
        $this->assertIsstring("NO");
       
       echo $Withdrawal->withdrawAmount('', "Nafees",1000,200, "REVIEWING", "NO");
    }
    /**
     * Invalid Amount 
     */
    public function testDepositAmountInValidAmt() 
    {

        $Withdrawal = new Withdrawal();
        $this->assertIsInt(6086000100057336);
        $this->assertIsstring("Nafees");
        $this->assertIsInt(1000);
        $this->assertIsInt(1200);
        $this->assertIsstring("REVIEWING");
        $this->assertIsstring("NO");
       
       echo $Withdrawal->withdrawAmount(6086000100057336, "Nafees",1000,1200, "REVIEWING", "NO");
    }

    /**
     * withdraw more than 500 from indivisual investment account  
     */
    public function testDepositAmountFromInvestmentIndivisualAcc() 
    {

        $Withdrawal = new Withdrawal();
        $this->assertIsInt(6086000100057336);
        $this->assertIsstring("Muhammad Nafees");
        $this->assertIsInt(1000);
        $this->assertIsInt(600);
        $this->assertIsstring("INVESTMENT");
        $this->assertIsstring("NO");
       
       echo $Withdrawal->withdrawAmount(6086000100057336, "Nafees",1000,600, "INVESTMENT", "INDIVISUAL");
    }
   
}