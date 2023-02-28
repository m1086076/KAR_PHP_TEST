<?php
use PHPUnit\Framework\TestCase;
require_once(dirname(__DIR__)."/Transfer.php"); 

class TransferTest extends TestCase
{
    /**
     * valid account number
     */
    public function testTransferAmountValid() 
    {
        $transfer = new Transfer();
        $withdrawAccount[6086000100057337] = array("accountHolderName"=>"Nafees","balance"=>500,"transferAmt"=>300,  "accountType"=>"INVESTMENT","investmentAccountType"=> "INDIVISUAL");
        $depositAccount[6086000100057338] = array("accountHolderName"=>"Muhammad Nafees","balance"=>100,  "accountType"=>"INVESTMENT","investmentAccountType"=> "INDIVISUAL");

        $this->assertIsArray($withdrawAccount);
        $this->assertIsArray($depositAccount);
       
        $result = $transfer->transferAmount($withdrawAccount,$depositAccount);
        $this->assertEquals($transfer->msg->showMessage("success"),$result);
    }

    /**
     * Invalid account number
     */
    public function testTransferAmountInValid() 
    {
        $transfer = new Transfer();
        $withdrawAccount[6086000100057337] = array("accountHolderName"=>"Nafees","balance"=>500,"transferAmt"=>300,  "accountType"=>"INVESTMENT","investmentAccountType"=> "INDIVISUAL");
        $depositAccount[6086000100057337] = array("accountHolderName"=>"Muhammad Nafees","balance"=>100,  "accountType"=>"INVESTMENT","investmentAccountType"=> "INDIVISUAL");

        $this->assertIsArray($withdrawAccount);
        $this->assertIsArray($depositAccount);
       
        $result = $transfer->transferAmount($withdrawAccount,$depositAccount);
        $this->assertEquals($transfer->msg->showMessage("TransferActInvalid"),$result);
    }
    
    /**
     * Invalid Amount 
     */
    public function testTransferAmountInValidAmt() 
    {
        $transfer = new Transfer();
        $withdrawAccount[6086000100057337] = array("accountHolderName"=>"Nafees","balance"=>500,"transferAmt"=>-500,  "accountType"=>"INVESTMENT","investmentAccountType"=> "INDIVISUAL");
        $depositAccount[6086000100057338] = array("accountHolderName"=>"Muhammad Nafees","balance"=>100,  "accountType"=>"INVESTMENT","investmentAccountType"=> "INDIVISUAL");

        $this->assertIsArray($withdrawAccount);
        $this->assertIsArray($depositAccount);
       
        $result = $transfer->transferAmount($withdrawAccount,$depositAccount);
        $this->assertEquals($transfer->msg->showMessage("InvalidAMT"),$result);
    }

    /**
     * Transfer more than balance amount  
     */
    public function testTransferAmountMoreThanBalance() 
    {
        $transfer = new Transfer();
        $withdrawAccount[6086000100057337] = array("accountHolderName"=>"Nafees","balance"=>500,"transferAmt"=>700,  "accountType"=>"INVESTMENT","investmentAccountType"=> "INDIVISUAL");
        $depositAccount[6086000100057338] = array("accountHolderName"=>"Muhammad Nafees","balance"=>100,  "accountType"=>"INVESTMENT","investmentAccountType"=> "INDIVISUAL");
        $this->assertIsArray($withdrawAccount);
        $this->assertIsArray($depositAccount);
       
        $result = $transfer->transferAmount($withdrawAccount,$depositAccount);
        $this->assertEquals($transfer->msg->showMessage("insufficientFund"),$result);
    }
   
}