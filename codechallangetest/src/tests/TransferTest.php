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
        $transfer->sender = new Account();
        $transfer->receiver = new Account();

        $withdrawAccount[6086000100057337] = array("accountHolderName"=>"Nafees","balance"=>500,"transferAmt"=>300,  "accountType"=>"INVESTMENT","investmentAccountType"=> "INDIVISUAL");
        $depositAccount[6086000100057338] = array("accountHolderName"=>"Muhammad Nafees","balance"=>100,  "accountType"=>"INVESTMENT","investmentAccountType"=> "INDIVISUAL");

        $transfer->sender->setAccountNumber(6086000100057337);
        $transfer->sender->setaccountHolderName($withdrawAccount[6086000100057337]['accountHolderName']);
        $transfer->sender->setAccountType($withdrawAccount[6086000100057337]['accountType']);
        $transfer->sender->setInvestmentAccountType($withdrawAccount[6086000100057337]['investmentAccountType']);
        $transfer->sender->setAccountBalance($withdrawAccount[6086000100057337]['balance']);
        $transfer->sender->setTransferAmmount($withdrawAccount[6086000100057337]['transferAmt']);
        $transfer->sender->setBankName($transfer->bank->bankName);

        $this->assertEquals('6086000100057337',$transfer->sender->getAccountNumber());
        $this->assertEquals("Nafees",$transfer->sender->getAccountHolderName());
        $this->assertEquals("INVESTMENT",$transfer->sender->getAccountType());
        $this->assertEquals("INDIVISUAL",$transfer->sender->getInvestmentAccountType());
        $this->assertEquals(500,$transfer->sender->getAccountBalance());
        $this->assertEquals("HDFC Bank",$transfer->sender->getBankName());
        $this->assertIsInt(300);//transfer Ammount

        $transfer->receiver->setAccountNumber(6086000100057338);
        $transfer->receiver->setaccountHolderName($depositAccount[6086000100057338]['accountHolderName']);
        $transfer->receiver->setAccountType($depositAccount[6086000100057338]['accountType']);
        $transfer->receiver->setInvestmentAccountType($depositAccount[6086000100057338]['investmentAccountType']);
        $transfer->receiver->setAccountBalance($depositAccount[6086000100057338]['balance']);
        $transfer->receiver->setBankName($transfer->bank->bankName);

        $this->assertEquals('6086000100057338',$transfer->receiver->getAccountNumber());
        $this->assertEquals("Muhammad Nafees",$transfer->receiver->getAccountHolderName());
        $this->assertEquals("INVESTMENT",$transfer->receiver->getAccountType());
        $this->assertEquals("INDIVISUAL",$transfer->receiver->getInvestmentAccountType());
        $this->assertEquals(100,$transfer->receiver->getAccountBalance());
        $this->assertEquals("HDFC Bank",$transfer->receiver->getBankName());
       
        $result = $transfer->transferAmount($withdrawAccount,$depositAccount);

        $res_sender = json_decode($result['postTransfer_senderAccount'],true);
        $res_receiver = json_decode($result['postTransfer_receiverAccount'],true);

        $this->assertEquals(400,$res_receiver['accountBalance']);
        $this->assertEquals(200,$res_sender['accountBalance']); 
    }

    /**
     * Invalid account number
     */
    public function testTransferAmountInValid() 
    {
        $transfer = new Transfer();
        $transfer->sender = new Account();
        $transfer->receiver = new Account();

        $withdrawAccount[6086000100057338] = array("accountHolderName"=>"Nafees","balance"=>500,"transferAmt"=>300,  "accountType"=>"INVESTMENT","investmentAccountType"=> "INDIVISUAL");
        $depositAccount[6086000100057338] = array("accountHolderName"=>"Muhammad Nafees","balance"=>100,  "accountType"=>"INVESTMENT","investmentAccountType"=> "INDIVISUAL");

        $transfer->sender->setAccountNumber(6086000100057338);
        $transfer->sender->setaccountHolderName($withdrawAccount[6086000100057338]['accountHolderName']);
        $transfer->sender->setAccountType($withdrawAccount[6086000100057338]['accountType']);
        $transfer->sender->setInvestmentAccountType($withdrawAccount[6086000100057338]['investmentAccountType']);
        $transfer->sender->setAccountBalance($withdrawAccount[6086000100057338]['balance']);
        $transfer->sender->setTransferAmmount($withdrawAccount[6086000100057338]['transferAmt']);
        $transfer->sender->setBankName($transfer->bank->bankName);

        $this->assertEquals('6086000100057338',$transfer->sender->getAccountNumber());
        $this->assertEquals("Nafees",$transfer->sender->getAccountHolderName());
        $this->assertEquals("INVESTMENT",$transfer->sender->getAccountType());
        $this->assertEquals("INDIVISUAL",$transfer->sender->getInvestmentAccountType());
        $this->assertEquals(500,$transfer->sender->getAccountBalance());
        $this->assertEquals("HDFC Bank",$transfer->sender->getBankName());
        $this->assertIsInt(300);//transfer Ammount

        $transfer->receiver->setAccountNumber(6086000100057338);
        $transfer->receiver->setaccountHolderName($depositAccount[6086000100057338]['accountHolderName']);
        $transfer->receiver->setAccountType($depositAccount[6086000100057338]['accountType']);
        $transfer->receiver->setInvestmentAccountType($depositAccount[6086000100057338]['investmentAccountType']);
        $transfer->receiver->setAccountBalance($depositAccount[6086000100057338]['balance']);
        $transfer->receiver->setBankName($transfer->bank->bankName);

        $this->assertEquals('6086000100057338',$transfer->receiver->getAccountNumber());
        $this->assertEquals("Muhammad Nafees",$transfer->receiver->getAccountHolderName());
        $this->assertEquals("INVESTMENT",$transfer->receiver->getAccountType());
        $this->assertEquals("INDIVISUAL",$transfer->receiver->getInvestmentAccountType());
        $this->assertEquals(100,$transfer->receiver->getAccountBalance());
        $this->assertEquals("HDFC Bank",$transfer->receiver->getBankName());
       
        $result = $transfer->transferAmount($withdrawAccount,$depositAccount);
       
        $result = $transfer->transferAmount($withdrawAccount,$depositAccount);
        $this->assertEquals($transfer->msg->showMessage("TransferActInvalid"),$result);
    }
    
    /**
     * Invalid Amount 
     */
    public function testTransferAmountInValidAmt() 
    {
        $transfer = new Transfer();
        $transfer->sender = new Account();
        $transfer->receiver = new Account();

        $withdrawAccount[6086000100057337] = array("accountHolderName"=>"Nafees","balance"=>500,"transferAmt"=>-300,  "accountType"=>"INVESTMENT","investmentAccountType"=> "INDIVISUAL");
        $depositAccount[6086000100057338] = array("accountHolderName"=>"Muhammad Nafees","balance"=>100,  "accountType"=>"INVESTMENT","investmentAccountType"=> "INDIVISUAL");

        $transfer->sender->setAccountNumber(6086000100057337);
        $transfer->sender->setaccountHolderName($withdrawAccount[6086000100057337]['accountHolderName']);
        $transfer->sender->setAccountType($withdrawAccount[6086000100057337]['accountType']);
        $transfer->sender->setInvestmentAccountType($withdrawAccount[6086000100057337]['investmentAccountType']);
        $transfer->sender->setAccountBalance($withdrawAccount[6086000100057337]['balance']);
        $transfer->sender->setTransferAmmount($withdrawAccount[6086000100057337]['transferAmt']);
        $transfer->sender->setBankName($transfer->bank->bankName);

        $this->assertEquals('6086000100057337',$transfer->sender->getAccountNumber());
        $this->assertEquals("Nafees",$transfer->sender->getAccountHolderName());
        $this->assertEquals("INVESTMENT",$transfer->sender->getAccountType());
        $this->assertEquals("INDIVISUAL",$transfer->sender->getInvestmentAccountType());
        $this->assertEquals(500,$transfer->sender->getAccountBalance());
        $this->assertEquals("HDFC Bank",$transfer->sender->getBankName());
        $this->assertIsInt(-300);//transfer Ammount

        $transfer->receiver->setAccountNumber(6086000100057338);
        $transfer->receiver->setaccountHolderName($depositAccount[6086000100057338]['accountHolderName']);
        $transfer->receiver->setAccountType($depositAccount[6086000100057338]['accountType']);
        $transfer->receiver->setInvestmentAccountType($depositAccount[6086000100057338]['investmentAccountType']);
        $transfer->receiver->setAccountBalance($depositAccount[6086000100057338]['balance']);
        $transfer->receiver->setBankName($transfer->bank->bankName);

        $this->assertEquals('6086000100057338',$transfer->receiver->getAccountNumber());
        $this->assertEquals("Muhammad Nafees",$transfer->receiver->getAccountHolderName());
        $this->assertEquals("INVESTMENT",$transfer->receiver->getAccountType());
        $this->assertEquals("INDIVISUAL",$transfer->receiver->getInvestmentAccountType());
        $this->assertEquals(100,$transfer->receiver->getAccountBalance());
        $this->assertEquals("HDFC Bank",$transfer->receiver->getBankName());
       
        $result = $transfer->transferAmount($withdrawAccount,$depositAccount);
        $this->assertEquals($transfer->msg->showMessage("InvalidAMT"),$result);
    }

    /**
     * Transfer more than balance amount  
     */
    public function testTransferAmountMoreThanBalance() 
    {
        $transfer = new Transfer();
        $transfer->sender = new Account();
        $transfer->receiver = new Account();

        $withdrawAccount[6086000100057337] = array("accountHolderName"=>"Nafees","balance"=>500,"transferAmt"=>700,  "accountType"=>"INVESTMENT","investmentAccountType"=> "INDIVISUAL");
        $depositAccount[6086000100057338] = array("accountHolderName"=>"Muhammad Nafees","balance"=>100,  "accountType"=>"INVESTMENT","investmentAccountType"=> "INDIVISUAL");

        $transfer->sender->setAccountNumber(6086000100057337);
        $transfer->sender->setaccountHolderName($withdrawAccount[6086000100057337]['accountHolderName']);
        $transfer->sender->setAccountType($withdrawAccount[6086000100057337]['accountType']);
        $transfer->sender->setInvestmentAccountType($withdrawAccount[6086000100057337]['investmentAccountType']);
        $transfer->sender->setAccountBalance($withdrawAccount[6086000100057337]['balance']);
        $transfer->sender->setTransferAmmount($withdrawAccount[6086000100057337]['transferAmt']);
        $transfer->sender->setBankName($transfer->bank->bankName);

        $this->assertEquals('6086000100057337',$transfer->sender->getAccountNumber());
        $this->assertEquals("Nafees",$transfer->sender->getAccountHolderName());
        $this->assertEquals("INVESTMENT",$transfer->sender->getAccountType());
        $this->assertEquals("INDIVISUAL",$transfer->sender->getInvestmentAccountType());
        $this->assertEquals(500,$transfer->sender->getAccountBalance());
        $this->assertEquals("HDFC Bank",$transfer->sender->getBankName());
        $this->assertIsInt(700);//transfer Ammount

        $transfer->receiver->setAccountNumber(6086000100057338);
        $transfer->receiver->setaccountHolderName($depositAccount[6086000100057338]['accountHolderName']);
        $transfer->receiver->setAccountType($depositAccount[6086000100057338]['accountType']);
        $transfer->receiver->setInvestmentAccountType($depositAccount[6086000100057338]['investmentAccountType']);
        $transfer->receiver->setAccountBalance($depositAccount[6086000100057338]['balance']);
        $transfer->receiver->setBankName($transfer->bank->bankName);

        $this->assertEquals('6086000100057338',$transfer->receiver->getAccountNumber());
        $this->assertEquals("Muhammad Nafees",$transfer->receiver->getAccountHolderName());
        $this->assertEquals("INVESTMENT",$transfer->receiver->getAccountType());
        $this->assertEquals("INDIVISUAL",$transfer->receiver->getInvestmentAccountType());
        $this->assertEquals(100,$transfer->receiver->getAccountBalance());
        $this->assertEquals("HDFC Bank",$transfer->receiver->getBankName());
       
        $result = $transfer->transferAmount($withdrawAccount,$depositAccount);
        $this->assertEquals($transfer->msg->showMessage("insufficientFund"),$result);
    }
   
}