# Wallet Funnding Blockonomics
 Fund your customers wallets using blockonmics, this simple system is built using core php as a lightweight adoption of the process.
 
 THE CASE:

You have a customers account or wallet on your simple store or digial finance system as the case may be, and you choose BITCOIN as your prefered means of collecting funds from your customers and for that you choose a simple BITCION payment merchant named Blockonomics, after following religiously their documentation, you hit a wall in the name of websocket error, you clearly can't use the any of the other options provided by blockonomics because you do not get feedback as a developer.
 
 SOLUTION:

This is not a recomened solution and by all means do not use for high traffic sites, it is meant to be a quick fix to the issue as you find a more permanent solution to your websocket error. Yes in retrospect the below solution can be optimized for use in high traffic sites but as stated this not a recomendable path. 

The button and widget features do not give you any response that is true, but we get a record of the transaction and we can save additional data alongside the record of the transactioin, which we would the make a call to the Blockonomics Transaction history API, to see if the trnsaction has been confirmed and if confirned we update our customers wallet/account. The following are the steps taken;

1. Create Unique Id on Blockonomics button click and store in the data database alongside the customers unique ID.(Pages involved are Wallet.php, uniqueId.php[ajax api call to store unique Id in datatbase.])
2. Give customer payment Success feedback and Notice that the Transaction takes three feedbacks before showing as completed. (Pages Involved are fund_success.php)
3. On redirect to customers page/dashboard Make Api Call to check status of Transaction using the Unique ID to search for specific customers Transaction. (Pages involved are fund_account.php[included in header.php])
4. Upon confirmation, update customers Account and change Unique ID status. (Pages involved same as No. 3)

INSTALLATION:
1. Download zip file or clone in any format that suites you, place file in your server's local envrironment e.g htdocs for xammp
2. Create database named "fund_wallet" and import or run fund_wallet.sql
3. Open/run in browser with Username: gamji and Password: 1234567890

Enjoy and remember to stay weird fam.
