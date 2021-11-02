<?php
// include('../functions/connect.php');
// session_start();
$digits = 7;
$randms = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
$today = date('Y-m-d');

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://www.blockonomics.co/api/merchant_orders?limit=500',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer api_key'
    ),
));

$response = curl_exec($curl);

curl_close($curl);
$response;
$json = json_decode($response, true);
$userID = $_SESSION['userid'];
$username = $_SESSION['username'];
$findAccount =  selectOne('accounts', ['users_id' => $userID]);
$cuid = $findAccount['uuid'];
$accountId = $findAccount['id'];
$fundStatus = $findAccount['uuid_status'];
$string = "customer_id: $username, cuid: $cuid";
$accountBalance = $findAccount['balance'];
if ($fundStatus == 0) {
    $length = count($json);
    $x = 0;
    foreach ($json as $index => $data) {
        $clientData = [
            'email' => $data['data']['emailid'],
            'extradata' => $data['data']['extradata'],
            'value' => $data['paid_satoshi'],
            'addr' => $data['address'],
            'status' => $data['status']
        ];

        if($clientData['value'] > 0){
            $usdValue = round(BTCtoUSD($clientData['value']) / 100000000);
            if($usdValue <= 0){
                 break;
            }
            if(!$usdValue){
                 break;
            }
        }
        

        if ($clientData['status'] == 2) {
            if ($string == $clientData['extradata']) {
                if ($usdValue >= 18) {
                    // $usdValue = $clientData['value'];
                    $newAccountBalance = $accountBalance + $usdValue;
                    if ($usdValue >= 50 && $usdValue < 100) {
                        $newAccountBalance += 10;
                    } elseif ($usdValue >= 100 && $usdValue < 200) {
                        $newAccountBalance += 20;
                    } elseif ($usdValue >= 200 && $usdValue < 500) {
                        $newAccountBalance += 40;
                    } elseif ($usdValue >= 500) {
                        $newAccountBalance += 100;
                    }
                    $updateAccount = update('accounts', $accountId, 'id', ['balance' => $newAccountBalance, 'uuid_status' => 1]);
                    if (!$updateAccount) {
                        $error = "Error:1 \n" . mysqli_error($connection); //checking for errors
                    } else {
                        $transactionData = [
                            'amount' => $usdValue,
                            'transaction_type' => "FUND ACCOUNT",
                            'transaction_date' => $today,
                            'reference_id' => $clientData['addr'],
                            'accounts_id' => $accountId
                        ];
                        $storeTransaction = insert('transaction', $transactionData);
                        // echo "Account Successfully funded";
                    }
                } else {
                    // $decription = "Amount credited is less than $20 - Your Account was not funded";
                    // $notificationData = [
                    //     'description' => $decription,
                    //     'userid' => $userID,
                    //     'action_by' => $userID,
                    // ];
                    // insert('notifications', $notificationData);
                    # notify your customers that the are underfunding, 
                    # the commented codes above is an example
                    $updateAccount = update('accounts', $accountId, 'id', ['uuid_status' => 1]);
                }
            } else{
                if($x == $length - 1){
                    $updateAccount = update('accounts', $accountId, 'id', ['uuid_status' => 1]);
                }
            }
        } else{
            if ($string != $clientData['extradata']){
                // payment not valid yet
                if($x == $length - 1){
                    $updateAccount = update('accounts', $accountId, 'id', ['uuid_status' => 1]);
                }
             }
        }
        $x++;
    }
}
