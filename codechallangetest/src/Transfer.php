<?php
require_once(dirname(__DIR__)."/classes/banking.php"); 
require_once(dirname(__DIR__)."/classes/Account.php"); 
require_once(dirname(__DIR__)."/classes/messages.php"); 
/**
 * This Transfer class used to transfer ammount in given account number from given account.
 * Here we are using two static array to store account data for multiple transfer 
 * we need to use valid account number, amount, account type ect. 
*/
class Transfer extends Messages
{   
    function __construct()
    {
        $this->bank = new Banking();
        $this->sender = new Account();
        $this->receiver = new Account();
        $this->msg = new Messages();
    }

    /**
     * In this function we do the data validation
     * In this function we passed account details array 
    */
    private function accountValidation($accountDetails)
    {
        $errorMsg='';
        foreach($accountDetails as $accountNo=>$accountDetails)
        {
            if(empty($accountNo) || empty($accountDetails['accountHolderName']) || ($accountDetails['accountType']!=$this->bank->account_type_rev 
            && $accountDetails['accountType']!=$this->bank->account_type_invt)){
                return $errorMsg=$this->msg->showMessage("InvalidAccount"); 
            }
            if($accountDetails['accountType']==$this->bank->account_type_invt && ($this->bank->account_type_indv!=$accountDetails['investmentAccountType'] 
            && $this->bank->account_type_corp!=$accountDetails['investmentAccountType'])){
                return $errorMsg=$this->msg->showMessage("InvalidAccTypeInv"); 
            }
        }
        return $errorMsg;
    }

    /**
     * TransferAmount function we passed two static array that will hold the value for next transaction
     * This fuction used to transfer amount from one account number to another account number
     * In one account, we will widhdraw ammount and same ammount we will deposit in given account
    */
    public function transferAmount($withdrawAccount, $depositAccount)
    {
        if($this->accountValidation($withdrawAccount)!='')
        {
           return $this->accountValidation($withdrawAccount);
        }
        if($this->accountValidation($depositAccount)!='')
        {
           return $this->accountValidation($depositAccount);
        }
        $withdrawActNo = array_keys($withdrawAccount)[0];
        $depositActNo = array_keys($depositAccount)[0];
        if($depositActNo==$withdrawActNo){
            return $this->msg->showMessage("TransferActInvalid");
        }

        if(!is_numeric($withdrawAccount[$withdrawActNo]['balance']) || !is_numeric($withdrawAccount[$withdrawActNo]['transferAmt']) ||  $withdrawAccount[$withdrawActNo]['transferAmt'] <= 0){
            return $this->msg->showMessage("InvalidAMT"); 
        }

        if($withdrawAccount[$withdrawActNo]['balance']<$withdrawAccount[$withdrawActNo]['transferAmt']){
            return $errorMsg=$this->msg->showMessage("insufficientFund"); 
        }

        if(!empty($withdrawActNo)){
            $this->sender->setAccountNumber($withdrawActNo);
            $this->sender->setaccountHolderName($withdrawAccount[$withdrawActNo]['accountHolderName']);
            $this->sender->setAccountType($withdrawAccount[$withdrawActNo]['accountType']);
            $this->sender->setInvestmentAccountType($withdrawAccount[$withdrawActNo]['investmentAccountType']);
            $this->sender->setAccountBalance($withdrawAccount[$withdrawActNo]['balance']);
            $this->sender->setTransferAmmount($withdrawAccount[$withdrawActNo]['transferAmt']);
            $this->sender->setBankName($this->bank->bankName);
        }

        if(!empty($depositActNo)){
            $this->receiver->setAccountNumber($depositActNo);
            $this->receiver->setaccountHolderName($depositAccount[$depositActNo]['accountHolderName']);
            $this->receiver->setAccountType($depositAccount[$depositActNo]['accountType']);
            $this->receiver->setInvestmentAccountType($depositAccount[$depositActNo]['investmentAccountType']);
            $this->receiver->setAccountBalance($depositAccount[$depositActNo]['balance']);
            $this->receiver->setBankName($this->bank->bankName);
        }
        $data['preTransfer_senderAccount'] = json_encode($this->sender);
        $data['preTransfer_receiverAccount'] = json_encode($this->receiver);

        
        if($this->sender->getAccountBalance()>=$this->sender->getTransferAmmount())
        {
            $this->sender->setAccountBalance($this->sender->getAccountBalance()-$this->sender->getTransferAmmount());
            $this->receiver->setAccountBalance($this->receiver->getAccountBalance()+$this->sender->getTransferAmmount());

            $data['postTransfer_senderAccount'] = json_encode($this->sender);
            $data['postTransfer_receiverAccount'] = json_encode($this->receiver);

            return $data;
            
        }
        else{
            return $errorMsg=$this->msg->showMessage("insufficientFund"); 
        }
    }
    
}
/*
$obj = new Transfer();
$withdrawAccount[123] = array("accountHolderName"=>"Nafees","accountType"=>"INVESTMENT","investmentAccountType"=>"INDIVISUAL","balance"=>"500","transferAmt"=>"200");
$depositAccount[1234] = array("accountHolderName"=>"Nafees","accountType"=>"INVESTMENT","investmentAccountType"=>"INDIVISUAL","balance"=>"500");
$res = $obj->transferAmount($withdrawAccount, $depositAccount);

print_r($res);*/

