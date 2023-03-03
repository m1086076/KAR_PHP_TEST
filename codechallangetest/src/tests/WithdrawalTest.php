<?php
use PHPUnit\Framework\TestCase;
require_once(dirname(dirname(__DIR__))."./classes/Banking.php");  

class WithdrawalTest extends TestCase
{
    /**
     * valid account number
     */
    public function testDepositAmountValid() 
    {
       $Withdrawal = new Banking();
       $Withdrawal->account->setAccountNumber(6086000100057336);
       $Withdrawal->account->setAccountHolderName("Nafees");
       $Withdrawal->account->setAccountType("REVIEWING");
       $Withdrawal->account->setInvestmentAccountType("NO");
       $Withdrawal->account->setAccountBalance(1000);
       $Withdrawal->account->setBankName($Withdrawal->bankName);
 
       $this->assertEquals('6086000100057336',$Withdrawal->account->getAccountNumber());
       $this->assertEquals("Nafees",$Withdrawal->account->getAccountHolderName());
       $this->assertEquals("REVIEWING",$Withdrawal->account->getAccountType());
       $this->assertEquals("NO",$Withdrawal->account->getInvestmentAccountType());
       $this->assertEquals(1000,$Withdrawal->account->getAccountBalance());
       $this->assertEquals("HDFC Bank",$Withdrawal->account->getBankName());
       $this->assertIsInt(300);//withdraw Ammount
       
       $result = $Withdrawal->withdraw($Withdrawal->account->getAccountNumber(), $Withdrawal->account->getAccountHolderName(),$Withdrawal->account->getAccountBalance(),300, $Withdrawal->account->getAccountType(), $Withdrawal->account->getInvestmentAccountType());

       $res_array = json_decode($result['postWithdrawal'],true);

       $this->assertEquals(700,$res_array['accountBalance']);
       
    }
    
    /**
     * Invalid account number
     */
    public function testwithdrawAmount() 
    {
       $Withdrawal = new Banking();
       $Withdrawal->account->setAccountNumber('');
       $Withdrawal->account->setAccountHolderName("Nafees");
       $Withdrawal->account->setAccountType("REVIEWING");
       $Withdrawal->account->setInvestmentAccountType("NO");
       $Withdrawal->account->setAccountBalance(1000);
       $Withdrawal->account->setBankName($Withdrawal->bankName);
 
       $this->assertEquals('','');
       $this->assertEquals("Nafees",$Withdrawal->account->getAccountHolderName());
       $this->assertEquals("REVIEWING",$Withdrawal->account->getAccountType());
       $this->assertEquals("NO",$Withdrawal->account->getInvestmentAccountType());
       $this->assertEquals(1000,$Withdrawal->account->getAccountBalance());
       $this->assertEquals("HDFC Bank",$Withdrawal->account->getBankName());
       $this->assertIsInt(300);//withdraw Ammount
       
       $result = $Withdrawal->withdraw('', $Withdrawal->account->getAccountHolderName(),$Withdrawal->account->getAccountBalance(),300, $Withdrawal->account->getAccountType(), $Withdrawal->account->getInvestmentAccountType());
       
       $this->assertEquals($Withdrawal->msg->showMessage("InvalidAccount"),$result); 
    }

    /**
     * Invalid Amount 
     */
    public function testDepositAmountInValidAmt() 
    {
        $Withdrawal = new Banking();
        $Withdrawal->account->setAccountNumber(6086000100057336);
        $Withdrawal->account->setAccountHolderName("Nafees");
        $Withdrawal->account->setAccountType("REVIEWING");
        $Withdrawal->account->setInvestmentAccountType("NO");
        $Withdrawal->account->setAccountBalance(1000);
        $Withdrawal->account->setBankName($Withdrawal->bankName);
  
        $this->assertEquals('6086000100057336',$Withdrawal->account->getAccountNumber());
        $this->assertEquals("Nafees",$Withdrawal->account->getAccountHolderName());
        $this->assertEquals("REVIEWING",$Withdrawal->account->getAccountType());
        $this->assertEquals("NO",$Withdrawal->account->getInvestmentAccountType());
        $this->assertEquals(1000,$Withdrawal->account->getAccountBalance());
        $this->assertEquals("HDFC Bank",$Withdrawal->account->getBankName());
        $this->assertIsInt(1300);//withdraw Ammount
        
        $result = $Withdrawal->withdraw($Withdrawal->account->getAccountNumber(), $Withdrawal->account->getAccountHolderName(),$Withdrawal->account->getAccountBalance(),1300, $Withdrawal->account->getAccountType(), $Withdrawal->account->getInvestmentAccountType());
       
        $this->assertEquals($Withdrawal->msg->showMessage("insufficientFund"),$result);
    }

    /**
     * withdraw more than 500 from indivisual investment account  
     */
    public function testDepositAmountFromInvestmentIndivisualAcc() 
    {

       $Withdrawal = new Banking();
       $Withdrawal->account->setAccountNumber(6086000100057336);
       $Withdrawal->account->setAccountHolderName("Nafees");
       $Withdrawal->account->setAccountType("INVESTMENT");
       $Withdrawal->account->setInvestmentAccountType("INDIVISUAL");
       $Withdrawal->account->setAccountBalance(1000);
       $Withdrawal->account->setBankName($Withdrawal->bankName);
 
       $this->assertEquals('6086000100057336',$Withdrawal->account->getAccountNumber());
       $this->assertEquals("Nafees",$Withdrawal->account->getAccountHolderName());
       $this->assertEquals("INVESTMENT",$Withdrawal->account->getAccountType());
       $this->assertEquals("INDIVISUAL",$Withdrawal->account->getInvestmentAccountType());
       $this->assertEquals(1000,$Withdrawal->account->getAccountBalance());
       $this->assertEquals("HDFC Bank",$Withdrawal->account->getBankName());
       $this->assertIsInt(600);//withdraw Ammount
       
       $result = $Withdrawal->withdraw($Withdrawal->account->getAccountNumber(), $Withdrawal->account->getAccountHolderName(),$Withdrawal->account->getAccountBalance(),600, $Withdrawal->account->getAccountType(), $Withdrawal->account->getInvestmentAccountType());

        $this->assertEquals($Withdrawal->msg->showMessage("IndivisualAccLimit"),$result);
    }
   
}