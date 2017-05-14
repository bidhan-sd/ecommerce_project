<?php
	$filepath = realpath(dirname(__FILE__));
	include_once ($filepath.'/../lib/Database.php');
	include_once ($filepath.'/../helpers/Format.php');
?>

<?php 

class Customer{
	private $db;
	private $fm;

	public function __construct(){
		$this->db = new Database();
		$this->fm = new Format();
	}
	public function customerRegistration($data){
		$name = mysqli_real_escape_string($this->db->link, $data['name']);
		$city = mysqli_real_escape_string($this->db->link, $data['city']);
		$zip = mysqli_real_escape_string($this->db->link, $data['zip']);
		$email = mysqli_real_escape_string($this->db->link, $data['email']);
		$country = mysqli_real_escape_string($this->db->link, $data['country']);
		$address = mysqli_real_escape_string($this->db->link, $data['address']);
		$phone = mysqli_real_escape_string($this->db->link, $data['phone']);
		$password = mysqli_real_escape_string($this->db->link, md5($data['password']));

		if($name == "" || $city == "" || $zip == "" || $email == "" || $country == "" || $address == "" || $phone == "" || $password == "" ){
			$msg = "<span class='error'>Fields must not be empty <!/span>";
			return $msg;
		}
		$mailquery = "SELECT * FROM tbl_customer WHERE email='$email' LIMIT 1";
		$mailchk = $this->db->select($mailquery);
		if($mailchk != false){
			$msg = "<span class='error'>Email already exist !</span>";
			return $msg;
		}else{
			$query = "INSERT INTO tbl_customer(name,city,zip,email,country,address,phone,password) VALUES('$name','$city','$zip','$email','$country','$address','$phone','$password')";
			$inserted_row = $this->db->insert($query);
			if ($inserted_row) {
				$msg = "<span class='success'>Customer Data Inserted Successfully !</span>";
				return $msg;
			}else{				
				$msg = "<span class='error'>Customer Data Not Inserted !</span>";
				return $msg;
			}
		}
	}
	public function customerLogin($data){
		$email = mysqli_real_escape_string($this->db->link, $data['email']);
		$password = mysqli_real_escape_string($this->db->link, md5($data['password']));
		if($email== "" || $password == "" ){
			$msg = "<span class='error'>Fields must not be empty! </span>";
			return $msg;
		}
		$query = "SELECT * FROM tbl_customer WHERE email='$email' AND password='$password'";
		$result = $this->db->select($query);
		if ($result != false) {
			$value = $result->fetch_assoc();
			Session::set("cuslogin",true);
			Session::set("cmrId",$value['id']);
			Session::set("cmrName",$value['name']);
			header("Location:cart.php");
		}else{
			$msg = "<span class='error'>Email or password not match! </span>";
			return $msg;
		}
	}
	//Show data into inbox view detais menu .
	public function getCustomerData($id){
		$query = "SELECT * FROM tbl_customer WHERE id='$id'";
		$result = $this->db->select($query);
		return $result;
	}
	public function customerUpdate($data,$cmrId){
		$name = mysqli_real_escape_string($this->db->link, $data['name']);
		$city = mysqli_real_escape_string($this->db->link, $data['city']);
		$zip = mysqli_real_escape_string($this->db->link, $data['zip']);
		$email = mysqli_real_escape_string($this->db->link, $data['email']);
		$country = mysqli_real_escape_string($this->db->link, $data['country']);
		$address = mysqli_real_escape_string($this->db->link, $data['address']);
		$phone = mysqli_real_escape_string($this->db->link, $data['phone']);

		if($name == "" || $city == "" || $zip == "" || $email == "" || $country == "" || $address == "" || $phone == ""){
			$msg = "<span class='error'>Fields must not be empty <!/span>";
			return $msg;
		}else{

			$query = "UPDATE tbl_customer SET name='$name',address='$address',city='$city',country='$country',zip='$zip',phone='$phone',email='$email' WHERE id = '$cmrId'";
			$updated_row = $this->db->update($query);
			if ($updated_row) {
				$msg = "<span class='success'>Customer Data Updated Successfully !</span>";
				return $msg;
			}else{				
				$msg = "<span class='error'>Customer Data Not Updated !</span>";
				return $msg;
			}
		}
	}
}

?>