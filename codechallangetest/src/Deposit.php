<?php
require_once(dirname(__DIR__)."/classes/banking.php"); 
require_once(dirname(__DIR__)."/classes/Account.php"); 
require_once(dirname(__DIR__)."/classes/messages.php"); 
/**
 * This Deposit class used to deposit ammount in given account number
 * Here we are using static array to store account data for next deposit 
 * we need to use valid account number, amount, account type ect. 
*/
class Deposit extends Messages
{   
    function __construct()
    {
        $this->account = new Account();
        $this->bank = new Banking();
        $this->msg = new Messages();
    }

    /**
     * depositAmount function used to deposit amount in given account number
     *  
    */
    public function depositAmount($depositAccount, $accountHolderName, $depositAmount, $accountType, $investmentAccType="NO",$balance=0)
    {  
        if(empty($depositAccount) || empty($accountHolderName) || ($accountType!=$this->bank->account_type_rev 
        && $accountType!=$this->bank->account_type_invt)){
            return $this->msg->showMessage("InvalidAccount"); 
        }
        if($accountType==$this->bank->account_type_invt && ($this->bank->account_type_indv!=$investmentAccType 
        && $this->bank->account_type_corp!=$investmentAccType)){
            return $this->msg->showMessage("InvalidAccTypeInv"); 
        }
        if(!is_numeric($depositAmount) || $depositAmount <= 0){
            return $this->msg->showMessage("InvalidAMT"); 
        }

        /**
         * save account details in account object
        */
        $this->account->setAccountNumber($depositAccount);
        $this->account->setaccountHolderName($accountHolderName);
        $this->account->setAccountType($accountType);
        $this->account->setInvestmentAccountType($investmentAccType);
        $this->account->setAccountBalance($balance);
        $this->account->setBankName($this->bank->bankName);

        $data['preDeposit'] = json_encode($this->account);
        if($depositAmount > 0){
            /**
            * diposit ammount in account object
            */
            $this->account->setAccountBalance($this->account->getAccountBalance()+$depositAmount);
            $data['postDeposit'] = json_encode($this->account);

            return $data; 
        }
        else{
            return $this->msg->showMessage("failled");
        }
    }
    
}

