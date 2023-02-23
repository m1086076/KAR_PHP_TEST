<?php
require_once(dirname(__DIR__)."/classes/banking.php");  
require_once(dirname(__DIR__)."/classes/messages.php"); 

class Withdrawal extends Messages
{
    private static $accountDetails = array();
    
    function __construct()
    {
        $this->bankDetails = new Banking();
        $this->msg = new Messages();
    }

    public function withdrawAmount($withdrawalAccount, $accountHolderName,$accountBalance,$withdrawAmount, $accountType, $investmentAccType="NO")
    {
       
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
        
        
        if(!array_key_exists($withdrawalAccount, self::$accountDetails) && !empty($withdrawalAccount)){
            self::$accountDetails[$withdrawalAccount]['accountHolderName'] = $accountHolderName ?? null;
            self::$accountDetails[$withdrawalAccount]['balance'] = $accountBalance ?? 1000 ;
            self::$accountDetails[$withdrawalAccount]['accountType'] = $accountType;
            self::$accountDetails[$withdrawalAccount]['investmentAccountType'] = $investmentAccType;
            self::$accountDetails[$withdrawalAccount]['bank'] =  $this->bankDetails->bankName ?? 'HDFC Bank';
        }
        if(self::$accountDetails[$withdrawalAccount]['balance']<$withdrawAmount){
            return $this->msg->showMessage("insufficientFund"); 
        }
        if(isset(self::$accountDetails[$withdrawalAccount]['balance']) && !empty($withdrawalAccount) &&  self::$accountDetails[$withdrawalAccount]['balance']-=$withdrawAmount){
            return $this->msg->showMessage("success");
        }
        else{
            return $this->msg->showMessage("failled");
        }
    }
}