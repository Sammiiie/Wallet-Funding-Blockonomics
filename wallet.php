<?php
$page = "wallet";

include('header.php');
$uuID = generateRandomString(20);
?>
<!-- content begins here -->
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Fund Wallet</div>
                <div class="card-body">
                    <input type="text" value="<?php echo $userId ?>" id="client" name="client" hidden>
                    <input type="text" value="<?php echo $uuID ?>" id="uuId" name="uuId" hidden>
                    <style>
                        #blockoPayModal.modal .modal-footer small {
                            display: none;
                        }
                    </style>
                    <a href="" id="btn-deposit" class="blockoPayBtn btn btn-primary" data-toggle="modal" data-extra="customer_id: <?php echo $_SESSION['username'] ?>, cuid: <?php echo $uuID ?>" data-uid=e9d4a334d0704357>CLick to Pay</a>
                    <script>
                        $(document).ready(function() {

                            $('#btn-deposit').on("click", function() {
                                var client = $('#client').val();
                                var uuId = $('#uuId').val();
                                // $('#btn-deposit').hide();

                                $.ajax({
                                    url: "uniqueId.php",
                                    method: "POST",
                                    data: {
                                        client: client,
                                        uuId: uuId
                                    },
                                    success: function(data) {
                                        $('#payData').html(data);
                                    }
                                })
                            });

                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- content ends here -->


<?php

include('footer.php');

?>
<!-- blockonomics -->
<script src="https://www.blockonomics.co/js/pay_button.js"></script>