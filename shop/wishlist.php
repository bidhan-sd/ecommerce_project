<?php include "inc/header.php"; ?>
<?php
	if (isset($_GET['delwlistid'])) {
		//$productId = $_GET['delwlistid'];
		$productId = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['delwlistid']);  
		//$cmrId use from header page.
		 $delWish = $pd->delWlistData($cmrId,$productId);
	}

?>
 <div class="main">
    <div class="content">
    	<div class="cartoption">		
			<div class="cartpage">
	<h2>Wish list</h2>
	<?php
		if (isset($delWish)) {
			echo $delWish;
		}
	?>
		<table class="tblone">
			<tr>
				<th>Sl</th>
				<th>Product Name</th>
				<th>Price</th>
				<th>Quantity</th>
				<th>Action</th>
			</tr>

		<?php 
			//$cmrId means session id come form header page so don't need redeclear here just use.
			$getPd = $pd->getWlistData($cmrId);
			if($getPd){
				$i=0;
				while ($result = $getPd->fetch_assoc()) {
				$i++;
		?>
		<tr>
			<td><?php echo $i; ?></td>
			<td><?php echo $result['productName']; ?></td>
			<td>$<?php echo $result['price']; ?></td>
			<td><img src="admin/<?php echo $result['image']; ?>" alt=""/></td>

			<td>
			<a href="details.php?proid=<?php echo $result['productId']; ?>">Buy Now </a> || 
			<a href="?delwlistid=<?php echo $result['productId']; ?>">Remove</a>
			</td>
			</tr>
		<?php } } ?>
			
		</table>
	</div>
	<div class="shopping">
		<div class="shopleft" style="width: 100%;text-align: center;">
			<a href="index.php"> <img src="images/shop.png" alt="" /></a>
		</div>
	</div>
    	</div>  	
       <div class="clear"></div>
    </div>
 </div>
 <?php include "inc/footer.php"; ?>