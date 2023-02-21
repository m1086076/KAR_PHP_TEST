<?php
require_once(dirname(__DIR__)."/config/configs.php"); 
require_once(dirname(__DIR__)."/classes/messages.php"); 

class Transfer extends Messages
{
    public $bankName;
    public $account_type_invt;
    public $account_type_rev;
    public $account_type_indv;
    public $account_type_corp;
    public $withdrawalLimit;
    private static $withdrawAccountDetails = array();
    private static $depositAccountDetails = array();
    
    
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
    private function accountValidation($accountDetails)
    {
        $errorMsg='';
        foreach($accountDetails as $accountNo=>$accountDetails)
        {
            if(empty($accountNo) || empty($accountDetails['accountHolderName']) || ($accountDetails['accountType']!=$this->account_type_rev 
            && $accountDetails['accountType']!=$this->account_type_invt)){
                return $errorMsg=$this->msg->showMessage("InvalidAccount"); 
            }
            if($accountDetails['accountType']==$this->account_type_invt && ($this->account_type_indv!=$accountDetails['investmentAccountType'] 
            && $this->account_type_corp!=$accountDetails['investmentAccountType'])){
                return $errorMsg=$this->msg->showMessage("InvalidAccTypeInv"); 
            }
        }
        return $errorMsg;
    }

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

        if(!array_key_exists($withdrawActNo, self::$withdrawAccountDetails) && !empty($withdrawActNo)){
            self::$withdrawAccountDetails[$withdrawActNo]['balance'] = $withdrawAccount[$withdrawActNo]['balance'];
            self::$withdrawAccountDetails[$withdrawActNo]['accountHolderName'] = $withdrawAccount[$withdrawActNo]['accountHolderName'];
            self::$withdrawAccountDetails[$withdrawActNo]['accountType'] = $withdrawAccount[$withdrawActNo]['accountType'];;
            self::$withdrawAccountDetails[$withdrawActNo]['investmentAccountType'] = $withdrawAccount[$withdrawActNo]['investmentAccountType'];
            self::$withdrawAccountDetails[$withdrawActNo]['bank'] =  $this->bankName ?? 'HDFC Bank';
        }

        if(!array_key_exists($depositActNo, self::$depositAccountDetails) && !empty($depositActNo)){
            self::$depositAccountDetails[$depositActNo]['balance'] = $depositAccount[$depositActNo]['balance'];
            self::$depositAccountDetails[$depositActNo]['accountHolderName'] = $depositAccount[$depositActNo]['accountHolderName'];
            self::$depositAccountDetails[$depositActNo]['accountType'] = $depositAccount[$depositActNo]['accountType'];;
            self::$depositAccountDetails[$depositActNo]['investmentAccountType'] = $depositAccount[$depositActNo]['investmentAccountType'];
            self::$depositAccountDetails[$depositActNo]['bank'] =  $this->bankName ?? 'HDFC Bank';
        }
        

        
        if(self::$withdrawAccountDetails[$withdrawActNo]['balance']>$withdrawAccount[$withdrawActNo]['transferAmt']){
            if((self::$withdrawAccountDetails[$withdrawActNo]['balance'] -= $withdrawAccount[$withdrawActNo]['transferAmt']) && (self::$depositAccountDetails[$depositActNo]['balance']+=$withdrawAccount[$withdrawActNo]['transferAmt'])){
                return $this->msg->showMessage("success");
            }
            else {
                return $this->msg->showMessage("failled");
            }
        }
        else{
            return $errorMsg=$this->msg->showMessage("insufficientFund"); 
        }
    }
    
}

