
<?php
	$filepath = realpath(dirname(__FILE__));
	include_once ($filepath.'/../lib/Database.php');
	include_once ($filepath.'/../helpers/Format.php');
?>
<?php 

class Cart{
	private $db;
	private $fm;

	public function __construct(){
		$this->db = new Database();
		$this->fm = new Format();
	}
	public function addToCart($quantity,$id){
		$quantity = $this->fm->validation($quantity);
		$quantity = mysqli_real_escape_string($this->db->link, $quantity);
		$productId = mysqli_real_escape_string($this->db->link, $id);
		$sId = session_id();

		$squery = "SELECT * FROM tbl_product WHERE productId = '$productId'";
		$result = $this->db->select($squery)->fetch_assoc();

		$productName = $result['productName'];
		$price       = $result['price'];
		$image       = $result['image'];

		$cquery = "SELECT * FROM tbl_cart WHERE productId = '$productId' AND sId='$sId'";
		$getPro = $this->db->select($cquery);
		if($getPro){
			$msg = "Product already added !";
			return $msg;
		}else{
			$query = "INSERT INTO tbl_cart(sId,productId,productName,price,quantity,image) VALUES('$sId','$productId','$productName','$price','$quantity','$image')";		
			$inserted_row = $this->db->insert($query);

			if ($inserted_row){
				header("Location:cart.php");
			}else{
				header("Location:404.php");
			}
		}
	}
	public function getCartProduct(){
		$sId = session_id();
		$query = "SELECT * FROM tbl_cart WHERE sId = '$sId'";
		$result = $this->db->select($query);
		return $result;
	}
	public function updateCart($cartId,$quantity){

		$cartId = $this->fm->validation($cartId);
		$cartId = mysqli_real_escape_string($this->db->link, $cartId);
		$quantity = $this->fm->validation($quantity);
		$quantity = mysqli_real_escape_string($this->db->link, $quantity);
		$query = "UPDATE tbl_cart SET quantity = '$quantity' WHERE cartId='$cartId' ";
		$updated_row = $this->db->update($query);
		if ($updated_row){
			header("Location:cart.php");
		}else{
			$msg = "<span class='error'>Quantity Not Updated !<span>";
			return $msg;
		}
	}
	public function delProductBycart($delId){
		$delId = $this->fm->validation($delId);
		$delId = mysqli_real_escape_string($this->db->link, $delId);

		$query = "DELETE FROM tbl_cart WHERE cartId = '$delId'";
		$deldata = $this->db->delete($query);
		if ($deldata) {
			echo "<script>window.location='cart.php';</script>";
		}else{
			$msg = "<span class='error'>Product Not Deleted !<span>";
			return $msg;
		}
	}
	//Checking database cart table how much row here.
	//use Menu and somewhere.
	public function checkCartTable(){
		$sId = session_id();
		$query = "SELECT * FROM tbl_cart WHERE sId = '$sId'";
		$result = $this->db->select($query);
		return $result;
	}
	//Delete cart data when user logout.
	public function delCustomerCart(){
		$sId = session_id();
		$query = "DELETE FROM tbl_cart WHERE sId='$sId'";
		$this->db->delete($query);
	}
	//For order product implement this code into paymentoffline.php page.
	public function orderProduct($cmrId){
		$sId = session_id();
		$query = "SELECT * FROM tbl_cart WHERE sId = '$sId'";
		$getPro = $this->db->select($query);
		if ($getPro) {
			while ($result   = $getPro->fetch_assoc()) {
				$productId   = $result['productId'];
				$productName = $result['productName'];
				$quantity    = $result['quantity'];
				$price       = $result['price'] * $quantity;
				$image       = $result['image'];
				$query       = "INSERT INTO tbl_order(cmrId,productId,productName,quantity,price,image) VALUES('$cmrId','$productId','$productName','$quantity','$price','$image')";		
				$inserted_row = $this->db->insert($query);
			}
		}
	}
	//Success page a implement korchi.
	public function payableAmount($cmrId){
		$query = "SELECT price FROM tbl_order WHERE cmrId = '$cmrId' AND date = now()";
		$result = $this->db->select($query);
		return $result;
	}

	//Orderdetails a implement korchi.
	public function getOrderProduct($cmrId){
		$query = "SELECT * FROM tbl_order WHERE cmrId = '$cmrId' ORDER BY date DESC";
		$result = $this->db->select($query);
		return $result;
	}

	//
	public function checkOrder($cmrId){
		$query = "SELECT * FROM tbl_order WHERE cmrId = '$cmrId'";
		$result = $this->db->select($query);
		return $result;
	}

	//
	public function getAllOrderProduct(){
		$query = "SELECT * FROM tbl_order ORDER BY date DESC";
		$result = $this->db->select($query);
		return $result;
		
	}
	//Shifted product useing this method into inbox.php
	public function productShifted($id,$price,$time){
		$id = mysqli_real_escape_string($this->db->link, $id);
		$price = mysqli_real_escape_string($this->db->link, $price);
		$time = mysqli_real_escape_string($this->db->link, $time);
		$query = "UPDATE tbl_order SET status = '1' WHERE cmrId='$id' AND date='$time' AND price = '$price'";
			$updated_row = $this->db->update($query);
			if ($updated_row){
				$msg = "<span class='success'> Updated Successfully !<span>";
				return $msg;
			}else{
				$msg = "<span class='error'>Not Updated !<span>";
				return $msg;
			}
	}
	//Deleted product useing this method into inbox.php
	public function delProductShifted($id,$price,$time){
		$id = mysqli_real_escape_string($this->db->link, $id);
		$price = mysqli_real_escape_string($this->db->link, $price);
		$time = mysqli_real_escape_string($this->db->link, $time);

		$query = "DELETE FROM tbl_order  WHERE cmrId='$id' AND date='$time' AND price = '$price'";
		$deldata = $this->db->delete($query);
		if ($deldata) {
		$msg = "<span class='success'>Data deleted Successfully !<span>";
			return $msg;
		}else{
			$msg = "<span class='error'>Data Not Deleted !<span>";
			return $msg;
		}
	}
	//
	public function productShiftConfirm($id,$price,$time){
		$id = mysqli_real_escape_string($this->db->link, $id);
		$price = mysqli_real_escape_string($this->db->link, $price);
		$time = mysqli_real_escape_string($this->db->link, $time);
		$query = "UPDATE tbl_order SET status = '2' WHERE cmrId='$id' AND date='$time' AND price = '$price'";
			$updated_row = $this->db->update($query);
			if ($updated_row){
				$msg = "<span class='success'> Updated Successfully !<span>";
				return $msg;
			}else{
				$msg = "<span class='error'>Not Updated !<span>";
				return $msg;
			}
	}
}

?>