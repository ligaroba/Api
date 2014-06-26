<?php 

class DBHandler{
	private $conn;	
function __construct(){

	require_once "DbConnect.php";
	require_once "Encryption.php";
	
	$dbConn=new DbConnect();
	$this->conn=$dbConn->connect();
	//$this->SqlQuery();
	$this->newgroup("City Hopper");
	//$this->groupId();
	//$this->updategroup(8,"NRB Western");
	//$this->deleterecord('user',0,"assetid","KBH 5650");
	//$this->getUserId("d5f6f0557f551f1793a50972e541a9b1");
	//$this->checkLogin("0714814573", "otieno02");
	//$this->getUserId("ahfjafhjkfkjfjk");
	//$this->isUserExists('telNo','email','ligaroba2@gmail.com');
	//$this->createUser('0714814573','ligaye','robert','ligaroba@gmail.com','ligaroba2','otieno02','ahfjafhjkfkjfjkdf');
	//$this->getUserByEmail("ligaroba@gmail.com");
 	//$this->createAsset("KBH 5650","0714814573","Scania","Bus","Western",54,"personal","DR00145","T2245");
}
	function SqlQuery(){
		
		// Use query() to run a SELECT, which returns a resultset.
		
		$sql = 'SELECT * FROM group_table';
		$resultset = $this->conn->query($sql);
		foreach ($resultset as $row) {
			echo 'Using column name: id=', $row['group_id'], ' name=', $row['name'], '<br />';
			echo  $row[0] . '<br />';
			print_r($row);
			echo '<br />';
		}
	}
	
	// create new user function -- tested and it works to standards
public function createUser($telNO,$fname,$lname,$email,$username,$password) {

			
		$response = array();
	
	
		// First check if user already existed in db
	
	
if (!$this->isUserExists('email','telNo',$telNO) && !$this->isUserExists('username','email',$email) && !$this->isUserExists('telNo','username',$username)) {
			// Generating password hash
	
			$password_hash = PassHash::hash($password);
	
			// Generating API key
	
			$api_key = $this->generateApiKey();
			// insert query
			$stmt = $this->conn->query("INSERT INTO users(telNo,firstName,secondName,email,username,password,active,api_key)
					 values('$telNO','$fname','$lname','$email','$username','$password_hash',1,'$api_key')");
			
	
		
			// Check for successful insertion
			if ($stmt) {
				//echo "SUCCESSFULY ADDED";
				return $api_key;
				
	
			} else {
				// Failed to create user
				return "USER_CREATE_FAILED";
			}
          } else {
			// User with same email already existed in the db
		
			for($j==1;$j<=3;$j++){

			if($this->isUserExists('email','telNo',$telNO)==$j){
				return "NUMBER_ALREADY_EXISTS";
			//echo "NUMBER_ALREADY_EXISTS";
			}else if($this->isUserExists('telNo','email',$email)==$j){
				//echo "EMAIL_ALREADY_EXISTS";
				return "EMAIL_ALREADY_EXISTS"; 
			}else  if($this->isUserExists('telNo','username',$username)==$j){
				//echo "USERNAME_HAS_BEEN_TAKEN";
				return "USERNAME_HAS_BEEN_TAKEN";
			}
	 }//end of for loop 
	
	
	
		}
	
	}
public function createAsset($assetid,$telNO,$model,$category,$group_name,$capacity,$typeasset,$driverid,$assistantid) {

			
		$response = array();
	
	
		// First check if user already existed in db
	
	
if (!$this->isAssetExists('assetid',$newid)&&!$this->isAssetExists('driverid',$driverid)&&!$this->isAssetExists('assistantid',$assistantid)) {
		
$sql="INSERT INTO asset(assetid,telNo,model,category,group_name,capacity,typeasset,driverid,assistantid)
		  values('$assetid','$telNO','$model','$category','$group_name',$capacity,'$typeasset','$driverid','$assistantid')";
		
                $stmt = $this->conn->query($sql);
			
	
		
			// Check for successful insertion
			if ($stmt) {
				//echo "SUCCESSFULY ADDED";
				return $assetid; 
				
	
			} else {
				// Failed to create user
				//echo "ASSET_CREATE_FAILED";
				return "ASSET_CREATE_FAILED";
			}
		} else {
			// User with same email already existed in the db
		
			
			for($i==5;$i<=7;$i++){
			if($this->isAssetExists('assetid',$assetid)==$i){
				return "ASSET_ALREADY_EXISTS";
			}
			if($this->isAssetExists('driverid',$driverid)==$i){
				return"DRIVER_ALREADY_EXISTS";
			}
			if($this->isAssetExists('assistantid',$assistantid)==$i){
				return "ASSISTANT_ALREADY_EXISTS";
			}
			
		}//end of for loop 
	
	
	
		}
	
	}
	// getting user info using api_key -- tested and it works to standards
	
	public function getId($tablename,$col,$key) {
		$key_upper=strtoupper($key);
		$sql="SELECT * FROM $tablename WHERE $col = '$key_upper'";
		$item_data = $this->conn->query($sql);
		
		if ($item_data) {		
		
			return $item_data;
		

		} else {
			return NULL;
		}
	}

	// getting user info using api_key -- tested and it works to standards
	
	public function getAllItems($tablename) {
		$sql="SELECT * FROM $tablename where active=1";
		$all_item_data = $this->conn->query($sql);
		
		if ($all_item_data) {		
		
			return $all_item_data;
		

		} else {
			return NULL;
		}
	}
	
	// getting user data by primary key --- tested and it works to standards
	
	
	public function getApiKeyById($user_id) {
		$user_id_upper=strtoupper($user_id);
		$sql="SELECT api_key FROM users WHERE telNO = '$user_id_upper'";
		$api_key = $this->conn->query($sql,PDO::FETCH_ASSOC);
		
		if ($api_key)
		{
			return $api_key;
		} else {
			return NULL;
		}
	}

	// check if user details exists --tested it works to standards
	
	private function isUserExists($fetch,$key,$varData) {
		$varData_upper=strtoupper($varData);
		$sql="SELECT ". $fetch ." from users WHERE $key = '$varData_upper'";
		$stmt = $this->conn->query($sql);
		 if($stmt->fetch(PDO::FETCH_NUM)>0){
			
			if('email'==$key ){
				return EMAIL_ALREADY_EXISTS;
			}
			if('telNo'==$key ){
				return NUMBER_ALREADY_EXISTS;
			}
			if('username'==$key ){
				return USERNAME_HAS_BEEN_TAKEN;
			
			}else {
			return NULL;
		}
		//return $num_rows > 0;
			
		
	}
	}// check if user details exists --tested it works to standards
	
	private function isAssetExists($key,$varData) {
		$varData_upper=strtoupper($varData);
		$sql="SELECT * from asset WHERE $key = '$varData_upper'";
		$stmt = $this->conn->query($sql);
		
		 if($stmt->fetch(PDO::FETCH_NUM)>0){
		
			if('assetid'==$key ){
				return ASSET_ALREADY_EXISTS;
			}
			if('driverid'==$key ){
				return DRIVER_ALREADY_EXISTS;
			}
			if('assistantid'==$key ){
				return ASSISTANT_ALREADY_EXISTS;
			}
			
		}else {
			return NULL;
		}
		//return $num_rows > 0;
			
		
	}
private function isGroupExists($key,$varData) {
		$varData_upper=strtoupper($varData);
		$sql="SELECT * from group_table WHERE $key = '$varData_upper'";
		$stmt = $this->conn->query($sql);
		
		 if($stmt->fetch(PDO::FETCH_NUM)>0){
		
			if('group_name'==$key ){
				return GROUP_EXISTS;
			}
						
		}else {
			return NULL;
		}
		//return $num_rows > 0;
			
		
	}


//generating random key --tested it works to standards
	
	private function generateApiKey() {
	
		return md5(uniqid(rand(), true));
	
	
	
	}
	// validating api_key
	
	public function isValidApiKey($api_key) {
		$stmt = $this->conn->query("SELECT telNO from users WHERE api_key = ?");
		$num_rows = $stmt->fetch(PDO::FETCH_NUM);
		return $num_rows > 0;
	}


// --tested it works to standards	
public function newgroup($gruop_name) {
	//$group_id=uniqid(rand(), true);
  if($this->isGroupExists('group_name',$gruop_name)==null){

		$group_id=$this->generateId();
		$gruop_name_upper=strtoupper($gruop_name);
		$stmt = $this->conn->exec("INSERT INTO group_table values($group_id,'$gruop_name_upper');");
		if($stmt){
		   // echo "successfully entered";
			return $gruop_name ;
		}else{
			
		   
		return "GROUP_CREATE_FAILED";
			}
	     }else{
		    
		     return "GROUP_EXISTS";		
		}
	}
// --tested it works to standards	
	public function updategroup($group_id,$group_name) {
	
		$stmt = $this->conn->exec("update group_table set group_name='$group_name' where group_id=$group_id");
		if($stmt){
		echo "successfully updated";
		}else{
			
		echo "failed successfully";
		}
	}
public function deleterecord($tablename,$activestate,$colid,$rec_id) {
	          
	
		$stmt = $this->conn->exec("update $tablename set active=$activestate,deleted_at=now() where $colid='$rec_id'");
		if($stmt){
		echo "successfully deleted";
			return SUCCESSFUL;
		}else{
			return DELETION_FAILED;	
		echo "failed successfully";
		}
	}

// --tested it works to standards	
	public function generateId() {
		//echo $Id=rand(0,9);
		do{
		   	$Id=rand(0,100);
			
		}while($this->isGroupExists('group_id',$Id)!=NULL); 
	   return $Id;		
	}


	
	// check user login
	
/*	public function checkLogin($telNO, $password) {
	
		// fetching user by phone
		$sql="SELECT encrypassword  FROM users WHERE telNO ='$telNO'";
		$encrypassword= $this->conn->query($sql);
		
		if ($encrypassword->fetch(PDO::FETCH_NUM)>0) {
			// Found user with the phone num
			// Now verify the password
			
			//$password_hash = PassHash::hash($password);
			if (PassHash::check_password($encrypassword, $password)) {
				// User password is correct
				//return TRUE;
				echo "user exist";
				
			} else {
				// user password is incorrect
				//return WRONG_LOGIN_CREDENTIALS;
				echo "WRONG_LOGIN_CREDENTIALS";
			}
		} else {
			
			// user not existed with the email
				//return USER_DOESNT_EXIST;
			echo "WRONG_LOGIN_CREDENTIALS";
		}
	}
	
*/	
	
	
	
	
	
	// getting user by email
	
	public function getUserByEmail($email) {
		$sql="SELECT firstName,secondName, telNo, api_key,username FROM users WHERE email = '$email'";
		$stmt = $this->conn->query($sql,PDO::FETCH_ASSOC);
		
		if ($stmt) {
			
			foreach ($stmt as $row) {
			echo 'Using column name: id=', $row['username'], ' name=', $row['firstName'], '<br />';
			echo  $row[0] . '<br />';
			print_r($row);
			echo '<br />';
		}
		} else {
			return NULL;
		}
	}
	

	
	
	}



//$db=new DBHandler();

?>
