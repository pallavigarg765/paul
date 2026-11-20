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
			$ip = $_SERVER['REMOTE_ADDR'];
			$sqlsel = "SELECT * FROM iplog WHERE ip = '".$ip."'";
			$res1 = $db->query($sqlsel) or die(mysql_error()." ".$sqlsel);
			if($db->getNumRows($res1) >= 1){
				
			}else{
				$sqlinsert = "INSERT INTO iplog (username,ip) VALUES('".$row['username']."','".$ip."')";
				$db->query($sqlinsert) or die(mysql_error()." ".$sqlinsert);
			}
			// Modified by TECHNO EXPONENT.
			// No ip check for tester login.			
			if($username=='tester')
			{
				$_SESSION['username'] = $row['username'];
				$_SESSION['userid'] = $row['id'];
				if($_SESSION['come_from_categorywisePropertyListing']=='YES')
				{
					echo '3';
				}
				else 
				{
					echo '1';
				}
				
			}
			else if($username=='Paul')
			{
				$_SESSION['username'] = $row['username'];
				$_SESSION['userid'] = $row['id'];
				if($_SESSION['come_from_categorywisePropertyListing']=='YES')
				{
					echo '3';
				}
				else 
				{
					echo '1';
				}
			}
			else 
			{
				$sqlsel1 = "SELECT * FROM iplog WHERE ip='".$ip."' and active=0";
				$res = $db->query($sqlsel1)or die(mysql_error()." ".$sqlsel1);
				if($db->getNumRows($res) >= 1){	
					echo '2';		
				}else{
					$_SESSION['username'] = $row['username'];
					$_SESSION['userid'] = $row['id'];
					if($_SESSION['come_from_categorywisePropertyListing']=='YES')
					{
						echo '3';
					}
					else 
					{
						echo '1';
					}
				}
			}
			// Modified by TECHNO EXPONENT.
		}else{
			echo '0';
		}
	}
?>