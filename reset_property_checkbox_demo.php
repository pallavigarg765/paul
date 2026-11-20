<? 
  include('include/dbcon.php');
  $db = new DBConn();
  if(isset($_GET['checkbox']) && $_GET['checkbox']!="")
  {
    $checb = $_GET['checkbox'];
    $sql = "UPDATE copy_property SET ".$checb." = NULL";
    mysql_query($sql);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }