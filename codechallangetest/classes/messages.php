<?php

class Messages{
    public $res_msg = array();

    function __construct()
    {
        $this->res_msg = array("success"=>"Transaction has been successfuly done", "failled"=>"Transaction has been failled",
         "InvalidAccount"=>"Please use a valid account details", "InvalidAccountType"=>"Please use valid account type", 
         "InvalidAccTypeInv"=>"Please use valid investment account type", "IndivisualAccLimit"=>"You can't withdraw more than 500 dollar",
         "InvalidAMT"=>"Please enter valid amount","insufficientFund"=>"Insufficient Fund", "TransferActInvalid"=>"Same account transfer  not allowed"
        );
    }

    public function showMessage($msgCode)
    {
        return $this->res_msg[$msgCode];
    }
}