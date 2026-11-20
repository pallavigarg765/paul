<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/config.php');
	include('include/functions.php');
	$db = new DBConn();
	
	$response = "";
	if(isset($_REQUEST['id'])){
		$letters = $_REQUEST['letters'];
		$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
		$children = $_REQUEST['ch'];
		if($children == "true"){
			$sql = "SELECT property_id FROM property WHERE property_id LIKE '".$letters."%'";
		}else{
			$sql = "SELECT property_id FROM property WHERE property_id LIKE '".$letters."%' AND is_child = '0'";
		}
		$res = $db->query($sql);
		while($row=$db->fetchArray($res)){
			echo $row["id"]."###".$row["property_id"]."|";
		}
	}
	elseif(isset($_REQUEST['name'])){
		$letters = $_REQUEST['letters'];
		$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
		$sql = "SELECT firstname,lastname FROM contact WHERE firstname LIKE '%".$letters."%' OR lastname LIKE '%".$letters."%'";
		$res = $db->query($sql);
		while($row=$db->fetchArray($res)){
			echo $row["id"]."###".$row["firstname"]."-".$row["lastname"]."|";
		}
	}
	 	
?>