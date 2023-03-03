<?php
use PHPUnit\Framework\TestCase;
require_once(dirname(dirname(__DIR__))."./classes/Banking.php"); 

class DepositTest extends TestCase
{ 
    /**
     * valid account number
     */
    public function testDepositAmountValid() 
    {
       $deposit = new Banking();
       $deposit->account->setAccountNumber(6086000100057336);
       $deposit->account->setAccountHolderName("Nafees");
       $deposit->account->setAccountType("REVIEWING");
       $deposit->account->setInvestmentAccountType("NO");
       $deposit->account->setAccountBalance(500);
       $deposit->account->setBankName($deposit->bankName);

       $this->assertEquals(6086000100057336,$deposit->account->getAccountNumber());
       $this->assertEquals("Nafees",$deposit->account->getAccountHolderName());
       $this->assertEquals("REVIEWING",$deposit->account->getAccountType());
       $this->assertEquals("NO",$deposit->account->getInvestmentAccountType());
       $this->assertEquals(500,$deposit->account->getAccountBalance());
       $this->assertEquals("HDFC Bank",$deposit->account->getBankName());
       $this->assertIsInt(300);//Deposit Ammount

       $result = $deposit->deposit($deposit->account->getAccountNumber(), $deposit->account->getAccountHolderName(), 300, $deposit->account->getAccountType(), $deposit->account->getInvestmentAccountType(),$deposit->account->getAccountBalance());

       $res_array = json_decode($result['postDeposit'],true);

       $this->assertEquals(800,$res_array['accountBalance']); 
    }

    /**
     * Invalid account number
     */
    public function testDepositAmountInValid() 
    {
      $deposit = new Banking();
      $deposit->account->setAccountNumber('');
      $deposit->account->setAccountHolderName("Nafees");
      $deposit->account->setAccountType("REVIEWING");
      $deposit->account->setInvestmentAccountType("NO");
      $deposit->account->setAccountBalance(500);
      $deposit->account->setBankName($deposit->bankName);

      $this->assertEquals('','');
      $this->assertEquals("Nafees",$deposit->account->getAccountHolderName());
      $this->assertEquals("REVIEWING",$deposit->account->getAccountType());
      $this->assertEquals("NO",$deposit->account->getInvestmentAccountType());
      $this->assertEquals(500,$deposit->account->getAccountBalance());
      $this->assertEquals("HDFC Bank",$deposit->account->getBankName());
      $this->assertIsInt(300);//Deposit Ammount

      $result = $deposit->deposit('', $deposit->account->getAccountHolderName(), 300, $deposit->account->getAccountType(), $deposit->account->getInvestmentAccountType(),$deposit->account->getAccountBalance());

       $this->assertEquals($deposit->msg->showMessage("InvalidAccount"),$result);
    }

    /**
     * Invalid Amount 
     */
    public function testDepositAmountInValidAmt() 
    {
      $deposit = new Banking();
      $deposit->account->setAccountNumber(6086000100057336);
      $deposit->account->setAccountHolderName("Nafees");
      $deposit->account->setAccountType("REVIEWING");
      $deposit->account->setInvestmentAccountType("NO");
      $deposit->account->setAccountBalance(500);
      $deposit->account->setBankName($deposit->bankName);

      $this->assertEquals('6086000100057336',$deposit->account->getAccountNumber());
      $this->assertEquals("Nafees",$deposit->account->getAccountHolderName());
      $this->assertEquals("REVIEWING",$deposit->account->getAccountType());
      $this->assertEquals("NO",$deposit->account->getInvestmentAccountType());
      $this->assertEquals(500,$deposit->account->getAccountBalance());
      $this->assertEquals("HDFC Bank",$deposit->account->getBankName());
      $this->assertIsInt(-300);//Deposit Ammount

      $result = $deposit->deposit($deposit->account->getAccountNumber(), $deposit->account->getAccountHolderName(), -300, $deposit->account->getAccountType(), $deposit->account->getInvestmentAccountType(),$deposit->account->getAccountBalance());

       $this->assertEquals($deposit->msg->showMessage("InvalidAMT"),$result);
    }
   
}