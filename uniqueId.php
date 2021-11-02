<?php
include('functions/methods.php');
session_start();
$digits = 7;
$randms = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
$today = date('Y-m-d');

if(isset($_POST['client']) && isset($_POST['uuId'])){
    $userID = $_POST['client'];
    $findAccount =  selectOne('accounts', ['users_id' => $userID]);
    $accountId = $findAccount['id'];
    $updateId = update('accounts', $accountId, 'id', ['uuid' => $_POST['uuId'], 'uuid_status' => 0]);
    if($updateId){
        echo "Success";
    }else{
        echo "Failure";
    }
}