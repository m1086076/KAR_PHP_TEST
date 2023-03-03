<?php
require_once(dirname(__DIR__)."/classes/banking.php"); 
require_once(dirname(__DIR__)."/classes/Account.php");  
require_once(dirname(__DIR__)."/classes/messages.php"); 
/**
 * This Withdrawal class used to Withdraw ammount in given account number.
 * Here we are using one static array to store account data for multiple Withdrawal 
 * we need to use valid account number, amount, account type ect. 
*/
class Withdrawal extends Messages
{   
    function __construct()
    {
        $this->bankDetails = new Banking();
        $this->account = new Account();
        $this->msg = new Messages();
    }

    /**
     * In this function we do the data validation
     * In this function we passed account details array 
    */
    private function accountValidation($withdrawalAccount, $accountHolderName,$accountBalance,$withdrawAmount, $accountType, $investmentAccType)
    {
        $errorMsg='';
        if(empty($withdrawalAccount) || empty($accountHolderName) || ($accountType!=$this->bankDetails->account_type_rev 
        && $accountType!=$this->bankDetails->account_type_invt)){
            return $this->msg->showMessage("InvalidAccount"); 
        }
        if($accountType==$this->bankDetails->account_type_invt && ($this->bankDetails->account_type_indv!=$investmentAccType 
        && $this->bankDetails->account_type_corp!=$investmentAccType)){
            return $this->msg->showMessage("InvalidAccTypeInv"); 
        }
        if(!is_numeric($withdrawAmount) || $withdrawAmount <= 0){
            return $this->msg->showMessage("InvalidAMT"); 
        }
        if(!is_numeric($accountBalance) || $accountBalance<=0){
            return $this->msg->showMessage("InvalidAMT"); 
        }
        if($accountBalance<$withdrawAmount){
            return $this->msg->showMessage("insufficientFund"); 
        }
        if($investmentAccType==$this->bankDetails->account_type_indv &&  $withdrawAmount>$this->bankDetails->withdrawalLimit){
            return $this->msg->showMessage("IndivisualAccLimit"); 
        }
        return $errorMsg;
    }

     /**
     * withdrawAmount function we used one static array that will hold the value for next transaction
     * This fuction used to withdraw amount from given account number
    */
    public function withdrawAmount($withdrawalAccount, $accountHolderName,$accountBalance,$withdrawAmount, $accountType, $investmentAccType="NO")
    {  
        $errorMsg = $this->accountValidation($withdrawalAccount, $accountHolderName,$accountBalance,$withdrawAmount, $accountType, $investmentAccType);
        if($errorMsg!=''){
            return $errorMsg;
        }
        else{
            /**
            * save account details in account object
            */
            $this->account->setAccountNumber($withdrawalAccount);
            $this->account->setaccountHolderName($accountHolderName);
            $this->account->setAccountType($accountType);
            $this->account->setInvestmentAccountType($investmentAccType);
            $this->account->setAccountBalance($accountBalance);
            $this->account->setBankName($this->bankDetails->bankName);

            $data['preWithdrawal'] = json_encode($this->account);

            if($this->account->getAccountBalance()<$withdrawAmount){
                return $this->msg->showMessage("insufficientFund"); 
            }
            /**
             * Withdrawal ammount from account object 
            */
            if($this->account->getAccountBalance()>0 && !empty($withdrawalAccount)){
                $this->account->setAccountBalance($this->account->getAccountBalance()-$withdrawAmount);
                $data['postWithdrawal'] = json_encode($this->account);

                return $data;
            }
            else{
                return $this->msg->showMessage("failled");
            }
        }
   }
}