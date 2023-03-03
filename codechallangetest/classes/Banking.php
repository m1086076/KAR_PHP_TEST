<?php
require_once(dirname(__DIR__)."/config/configs.php"); 
require_once(dirname(__DIR__)."/classes/Account.php"); 
require_once(dirname(__DIR__)."/classes/messages.php");

class Banking
{
    public $bankName;
    public $account_type_invt;
    public $account_type_rev;
    public $account_type_indv;
    public $account_type_corp;
    public $withdrawalLimit;

    function __construct()
    {
        $this->bankName = BANK;
        $this->account_type_invt = ACOUNT_TYPE_INVT;
        $this->account_type_rev = ACOUNT_TYPE_REV;
        $this->account_type_indv = ACOUNT_TYPE_INDV;
        $this->account_type_corp = ACOUNT_TYPE_CORP;
        $this->withdrawalLimit = WITHDRAWLIMIT;
        $this->account = new Account();
        $this->msg = new Messages();
    } 

    /**
     * deposit Amount function used to deposit amount in given account number
     *  
    */
    public function deposit($depositAccount, $accountHolderName, $depositAmount, $accountType, $investmentAccType="NO",$balance=0)
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

        /**
         * save account details in account object
        */
        $this->account->setAccountNumber($depositAccount);
        $this->account->setaccountHolderName($accountHolderName);
        $this->account->setAccountType($accountType);
        $this->account->setInvestmentAccountType($investmentAccType);
        $this->account->setAccountBalance($balance);
        $this->account->setBankName($this->bankName);

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

    /**
     * In this function we do the data validation
     * In this function we passed account details array 
    */
    private function withdrawalValidation($withdrawalAccount, $accountHolderName,$accountBalance,$withdrawAmount, $accountType, $investmentAccType)
    {
        $errorMsg='';
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
        return $errorMsg;
    }

     /**
     * withdrawAmount function we used one static array that will hold the value for next transaction
     * This fuction used to withdraw amount from given account number
    */
    public function withdraw($withdrawalAccount, $accountHolderName,$accountBalance,$withdrawAmount, $accountType, $investmentAccType="NO")
    {  
        $errorMsg = $this->withdrawalValidation($withdrawalAccount, $accountHolderName,$accountBalance,$withdrawAmount, $accountType, $investmentAccType);
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
            $this->account->setBankName($this->bankName);

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

   /**
     * In this function we do the data validation
     * In this function we passed account details array 
    */
    private function transferInputValidation($accountDetails)
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

    /**
     * TransferAmount function we passed two static array that will hold the value for next transaction
     * This fuction used to transfer amount from one account number to another account number
     * In one account, we will widhdraw ammount and same ammount we will deposit in given account
    */
    public function transfer($withdrawAccount, $depositAccount)
    {
        if($this->transferInputValidation($withdrawAccount)!='')
        {
           return $this->transferInputValidation($withdrawAccount);
        }
        if($this->transferInputValidation($depositAccount)!='')
        {
           return $this->transferInputValidation($depositAccount);
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

        $this->sender = new Account();
        $this->receiver = new Account();

        if(!empty($withdrawActNo)){
            $this->sender->setAccountNumber($withdrawActNo);
            $this->sender->setaccountHolderName($withdrawAccount[$withdrawActNo]['accountHolderName']);
            $this->sender->setAccountType($withdrawAccount[$withdrawActNo]['accountType']);
            $this->sender->setInvestmentAccountType($withdrawAccount[$withdrawActNo]['investmentAccountType']);
            $this->sender->setAccountBalance($withdrawAccount[$withdrawActNo]['balance']);
            $this->sender->setTransferAmmount($withdrawAccount[$withdrawActNo]['transferAmt']);
            $this->sender->setBankName($this->bankName);
        }

        if(!empty($depositActNo)){
            $this->receiver->setAccountNumber($depositActNo);
            $this->receiver->setaccountHolderName($depositAccount[$depositActNo]['accountHolderName']);
            $this->receiver->setAccountType($depositAccount[$depositActNo]['accountType']);
            $this->receiver->setInvestmentAccountType($depositAccount[$depositActNo]['investmentAccountType']);
            $this->receiver->setAccountBalance($depositAccount[$depositActNo]['balance']);
            $this->receiver->setBankName($this->bankName);
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
