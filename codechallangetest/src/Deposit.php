<?php
require_once(dirname(__DIR__)."/classes/banking.php"); 
require_once(dirname(__DIR__)."/classes/messages.php"); 
/**
 * This Deposit class used to deposit ammount in given account number
 * Here we are using static array to store account data for next deposit 
 * we need to use valid account number, amount, account type ect. 
*/
class Deposit extends Messages
{   
    private static $accountDetails = array();
    
    function __construct()
    {
        $this->bankDetails = new Banking();
        $this->msg = new Messages();
    }

    /**
     * depositAmount function used to deposit amount in given account number
     *  
    */
    public function depositAmount($depositAccount, $accountHolderName, $depositAmount, $accountType, $investmentAccType="NO")
    {  
        if(empty($depositAccount) || empty($accountHolderName) || ($accountType!=$this->bankDetails->account_type_rev 
        && $accountType!=$this->bankDetails->account_type_invt)){
            return $this->msg->showMessage("InvalidAccount"); 
        }
        if($accountType==$this->bankDetails->account_type_invt && ($this->account_type_indv!=$investmentAccType 
        && $this->bankDetails->account_type_corp!=$investmentAccType)){
            return $this->msg->showMessage("InvalidAccTypeInv"); 
        }
        if(!is_numeric($depositAmount) || $depositAmount <= 0){
            return $this->msg->showMessage("InvalidAMT"); 
        }

        /**
         * Check the value and hold it in $accountDetails array for next deposit  
        */
        if(!array_key_exists($depositAccount, self::$accountDetails) && !empty($depositAccount)){
            self::$accountDetails[$depositAccount]['accountHolderName'] = $accountHolderName ?? null;
            self::$accountDetails[$depositAccount]['balance'] = 0 ;
            self::$accountDetails[$depositAccount]['accountType'] = $accountType;
            self::$accountDetails[$depositAccount]['investmentAccountType'] = $investmentAccType;
            self::$accountDetails[$depositAccount]['bank'] =  $this->bankDetails->bankName ?? 'HDFC Bank';
        }
        if(isset(self::$accountDetails[$depositAccount]['balance']) && !empty($depositAccount) && self::$accountDetails[$depositAccount]['balance']+=$depositAmount){
            return $this->msg->showMessage("success");
        }
        else{
            return $this->msg->showMessage("failled");
        }
        return self::$accountDetails;
       
    }
    
}