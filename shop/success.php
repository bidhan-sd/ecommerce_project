<?php include "inc/header.php"; ?>
<?php 
    $login = Session::get("cuslogin");
    if ($login == false) {
        header("Location:login.php");
    }
?>
<style>
	.paymentsuccess{width: 500px;min-height: 200px;text-align: center;border: 1px solid #ddd;margin: 0 auto;padding: 20px;}
	.paymentsuccess h2{border-bottom: 1px solid #ddd;margin-bottom: 20px;padding-bottom: 10px;}
	.paymentsuccess p {font-size: 18px;line-height: 25px;text-align: left;}
</style>
 <div class="main">
    <div class="content">
    	<div class="section group">
			<div class="paymentsuccess">
			<h2>Payment</h2>
			<?php
				$cmrId = Session::get("cmrId");
				$amount = $ct->payableAmount($cmrId);
				if($amount){
					$sum = 0;
					while ($result = $amount->fetch_assoc()) {
						$price = $result['price'];
						$sum = $sum + $price;
					}
				}
			?>
				<p style="color:red;">Total Payable Amount (Including Vat) : $ <?php				
					$vat = $sum * 0.1;
					$total = $sum + $vat;			 
					echo $total;
				 ?></p>
				<p>Thanks for Purchase. Receive Your order Successfully. We will contact you as soon as Possible with delivary details. Here is your order Details ... <a href="orderdetails.php">Visit Here </a></p>
			</div>
 		</div>
 	</div>
</div>
<?php include "inc/footer.php"; ?>