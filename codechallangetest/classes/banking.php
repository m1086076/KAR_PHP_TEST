<?php
require_once(dirname(__DIR__)."/config/configs.php"); 

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
    }
    
}