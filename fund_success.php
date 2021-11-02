<?php
include('functions/methods.php');
session_start();
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
    foreach ($json as $data) {
        $clientData = [
            'email' => $data['data']['emailid'],
            'extradata' => $data['data']['extradata'],
            'value' => $data['paid_satoshi'],
            'addr' => $data['address'],
            'status' => $data['status']
        ];

        $usdValue = round(BTCtoUSD($clientData['value']) / 100000000);

        if ($clientData['status'] == 2) {
            if ($string == $clientData['extradata']) {
                if ($usdValue >= 18) {
                    // Bonuses given to customers based on the amount funded you can ignore this
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
                    $updateAccount = update('accounts', $accountId, 'id', ['uuid_status' => 1]);
                }
            }
        }
    }
}


?>
<html>

<head>
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
</head>
<style>
    body {
        text-align: center;
        padding: 40px 0;
        background: #EBF0F5;
    }

    h1 {
        color: #88B04B;
        font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
        font-weight: 900;
        font-size: 40px;
        margin-bottom: 10px;
    }

    p {
        color: #404F5E;
        font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
        font-size: 20px;
        margin: 0;
    }

    i {
        color: #9ABC66;
        font-size: 100px;
        line-height: 200px;
        margin-left: -15px;
    }

    .card {
        background: white;
        padding: 60px;
        border-radius: 4px;
        box-shadow: 0 2px 3px #C8D0D8;
        display: inline-block;
        margin: 0 auto;
    }
</style>

<body>
    <div class="card">
        <div style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
            <i class="checkmark">✓</i>
        </div>
        <h1>Success</h1>
        <p>Fund account Successful;<br /> It would take 3 confirmation before your account gets funded. <a href="index.php">redirect</a></p>
    </div>
</body>

</html>