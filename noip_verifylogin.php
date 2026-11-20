<? 
	include('include/dbcon.php');
	$db = new DBConn();
	session_start();
	$username = $_REQUEST['username'];
	$password = $_REQUEST['password'];
	if($username != '' && $password != ''){
		$sql = "SELECT * FROM user WHERE username='".$username."' and password='".$password."' AND active = 1";
		$res = $db->query($sql);
		if($db->getNumRows($res) >= 1){
			$row = $db->fetchArray($res);
			$_SESSION['username'] = $row['username'];
			$_SESSION['userid'] = $row['id'];
			$ip = $_SERVER['REMOTE_ADDR'];
			$sqlsel = "SELECT * FROM logip WHERE ip='".$ip."'";
			$res = $db->query($sql);
			if($db->getNumRows($res) >= 1){
			}else{
				$sqlinsert = "INSERT INTO iplog (username,ip) VALUES('".$row['username']."','".$ip."')";
				$db->query($sqlinsert);
			}
			echo '1';
		}else{
			echo '0';
		}
	}
?>