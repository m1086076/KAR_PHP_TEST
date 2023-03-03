<?php

class Account
{
    public $bankName;
    public $accountHolderName;
    public $accountNumber;
    public $accountType;
    public $accountBalance = 0;
    public $investmentAccountType;
    public $transferAmmount = 0;
    
    /** 
     * accountHolderName will be string 
     */
    public function setAccountHolderName($accountHolderName)
    {
        $this->accountHolderName = $accountHolderName;
    }

    public function getAccountHolderName() : string
    {
        return $this->accountHolderName;
    }

    /** 
     * accountNumber will be int
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;
    }

    public function getAccountNumber() : int
    {
        return $this->accountNumber;
    }

    /** 
     * accountType will be string
     */
    public function setAccountType($accountType) 
    {
        $this->accountType = $accountType;
    }

    public function getAccountType()
    {
        return $this->accountType;
    }

    /** 
     * investmentAccountType will be string
     */
    public function setInvestmentAccountType($investmentAccountType)
    {
        $this->investmentAccountType = $investmentAccountType;
    }

    public function getInvestmentAccountType()
    {
        return $this->investmentAccountType;
    }

    /** 
     * accountBalance will be float
     */
    public function setAccountBalance($accountBalance)
    {
        $this->accountBalance = $accountBalance>0 ? $accountBalance: 0;
    }

    public function getAccountBalance() : float
    {
        return $this->accountBalance;
    }

    /** 
     * Transfer ammount will be float
     */
    public function setTransferAmmount($transferAmmount)
    {
        $this->transferAmmount = $transferAmmount>0 ? $transferAmmount: 0;
    }

    public function getTransferAmmount() : float
    {
        return $this->transferAmmount;
    }

    /** 
     * set the bank name here
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;
    }

    public function getBankName()
    {
        return $this->bankName;
    }
    
}
