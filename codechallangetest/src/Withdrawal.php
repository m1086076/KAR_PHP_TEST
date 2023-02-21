<?php
require_once(dirname(__DIR__)."/config/configs.php"); 
require_once(dirname(__DIR__)."/classes/messages.php"); 

class Withdrawal extends Messages
{
    public $bankName;
    public $account_type_invt;
    public $account_type_rev;
    public $account_type_indv;
    public $account_type_corp;
    public $withdrawalLimit;
    private static $accountDetails = array();
    
    
    function __construct()
    {
        $this->bankName = BANK;
        $this->account_type_invt = ACOUNT_TYPE_INVT;
        $this->account_type_rev = ACOUNT_TYPE_REV;
        $this->account_type_indv = ACOUNT_TYPE_INDV;
        $this->account_type_corp = ACOUNT_TYPE_CORP;
        $this->withdrawalLimit = WITHDRAWLIMIT;
        $this->msg = new Messages();
    }

    public function withdrawAmount($withdrawalAccount, $accountHolderName,$accountBalance,$withdrawAmount, $accountType, $investmentAccType="NO")
    {
       
        if(empty($withdrawalAccount) || empty($accountHolderName) || ($accountType!=$this->account_type_rev 
        && $accountType!=$this->account_type_invt)){
            return $this->msg->showMessage("InvalidAccount"); 
        }
        if($accountType==$this->account_type_invt && ($this->account_type_indv!=$investmentAccType 
        && $this->account_type_corp!=$investmentAccType)){
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
        if($investmentAccType==$this->account_type_indv &&  $withdrawAmount>$this->withdrawalLimit){
            return $this->msg->showMessage("IndivisualAccLimit"); 
        }
        
        
        if(!array_key_exists($withdrawalAccount, self::$accountDetails) && !empty($withdrawalAccount)){
            self::$accountDetails[$withdrawalAccount]['accountHolderName'] = $accountHolderName ?? null;
            self::$accountDetails[$withdrawalAccount]['balance'] = $accountBalance ?? 1000 ;
            self::$accountDetails[$withdrawalAccount]['accountType'] = $accountType;
            self::$accountDetails[$withdrawalAccount]['investmentAccountType'] = $investmentAccType;
            self::$accountDetails[$withdrawalAccount]['bank'] =  $this->bankName ?? 'HDFC Bank';
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