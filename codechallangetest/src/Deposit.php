<?php
require_once(dirname(__DIR__)."/config/configs.php"); 
require_once(dirname(__DIR__)."/classes/messages.php"); 

class Deposit extends Messages
{
    public $bankName;
    public $account_type_invt;
    public $account_type_rev;
    public $account_type_indv;
    public $account_type_corp;
    
    private static $accountDetails = array();
    
    
    function __construct()
    {
        $this->bankName = BANK;
        $this->account_type_invt = ACOUNT_TYPE_INVT;
        $this->account_type_rev = ACOUNT_TYPE_REV;
        $this->account_type_indv = ACOUNT_TYPE_INDV;
        $this->account_type_corp = ACOUNT_TYPE_CORP;
        
        $this->msg = new Messages();
    }

    public function depositAmount($depositAccount, $accountHolderName, $depositAmount, $accountType, $investmentAccType="NO")
    {
       
        if(empty($depositAccount) || empty($accountHolderName) || ($accountType!=$this->account_type_rev 
        && $accountType!=$this->account_type_invt)){
            return $this->msg->showMessage("InvalidAccount"); 
        }
        if($accountType==$this->account_type_invt && ($this->account_type_indv!=$investmentAccType 
        && $this->account_type_corp!=$investmentAccType)){
            return $this->msg->showMessage("InvalidAccTypeInv"); 
        }
        if(!is_numeric($depositAmount) || $depositAmount <= 0){
            return $this->msg->showMessage("InvalidAMT"); 
        }
        if(!array_key_exists($depositAccount, self::$accountDetails) && !empty($depositAccount)){
            self::$accountDetails[$depositAccount]['accountHolderName'] = $accountHolderName ?? null;
            self::$accountDetails[$depositAccount]['balance'] = 0 ;
            self::$accountDetails[$depositAccount]['accountType'] = $accountType;
            self::$accountDetails[$depositAccount]['investmentAccountType'] = $investmentAccType;
            self::$accountDetails[$depositAccount]['bank'] =  $this->bankName ?? 'HDFC Bank';
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
