<?php
require_once(dirname(__DIR__)."/src/Deposit.php");
require_once(dirname(__DIR__)."/src/Transfer.php");
require_once(dirname(__DIR__)."/src/Withdrawal.php");

class apiURL
{
    public function deposit($input)
    {
        $deposit = new Deposit();
        if(!empty($input))
        {
            $depositAccount = $input['accountNumber'] ?? null;
            $accountHolderName = $input['accountHolderName'] ?? null;
            $depositAmount = $input['depositAmount'] ?? null;
            $accountType = $input['accountType'] ?? null;
            $investmentAccType = $input['investmentAccType'] ?? 'NO';
            $balance = $input['balance'] ?? null;

            return $deposit->depositAmount($depositAccount, $accountHolderName, $depositAmount, $accountType, $investmentAccType,$balance);
        }
        
    }

    public function withdraw($input)
    {
        $Withdrawal = new Withdrawal();
        if(!empty($input))
        {
            $withdrawalAccount = $input['accountNumber'] ?? null;
            $accountHolderName = $input['accountHolderName'] ?? null;
            $accountType = $input['accountType'] ?? null;
            $investmentAccType = $input['investmentAccType'] ?? 'NO';
            $accountBalance = $input['balance'] ?? null;
            $withdrawAmount = $input['withdrawAmount'] ?? 0;

            return $Withdrawal->withdrawAmount($withdrawalAccount, $accountHolderName,$accountBalance,$withdrawAmount, $accountType, $investmentAccType);
        }
        
    }

    public function transfer($input)
    {
        $transfer = new Transfer();
        if(!empty($input))
        {
            $snAccNo = $input['sender_accountNumber'] ?? null;
            $withdrawAccount[$snAccNo]['accountNumber'] = $snAccNo;
            $withdrawAccount[$snAccNo]['accountHolderName'] = $input['sender_accountHolderName'] ?? null;
            $withdrawAccount[$snAccNo]['transferAmt'] = $input['transferAmt'] ?? null;
            $withdrawAccount[$snAccNo]['accountType'] = $input['sender_accountType'] ?? null;
            $withdrawAccount[$snAccNo]['investmentAccountType'] = $input['sender_investmentAccType'] ?? 'NO';
            $withdrawAccount[$snAccNo]['balance'] = $input['sender_balance'] ?? null;

            $rAccNo = $input['receiver_accountNumber'] ?? null;
            $depositAccount[$rAccNo]['accountNumber'] = $rAccNo;
            $depositAccount[$rAccNo]['accountHolderName'] = $input['receiver_accountHolderName'] ?? null;
            $depositAccount[$rAccNo]['accountType'] = $input['receiver_accountType'] ?? null;
            $depositAccount[$rAccNo]['investmentAccountType'] = $input['receiver_investmentAccType'] ?? 'NO';
            $depositAccount[$rAccNo]['balance'] = $input['receiver_balance'] ?? null;

            return $transfer->transferAmount($withdrawAccount, $depositAccount);
        }
        
    }
}


$apiObj = new apiURL();
$action = !isset($_GET['action']) ? 'none' : strtolower($_GET['action']);

switch ($action) {
	case 'deposit':
		$result = $apiObj->deposit($_REQUEST);
        print_r($result);
       
	break;
	case 'withdraw':
		$result = $apiObj->withdraw($_REQUEST);
        print_r($result);
	break;
	case 'transfer':
		$result = $apiObj->transfer($_REQUEST);
        print_r($result);
	break;
	default:
		
		break;
    }