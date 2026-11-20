<?php
  include('include/security.php');
  include('include/dbcon.php');
  include('include/config.php');
  include('include/functions.php');
  $db = new DBConn();
  mysql_select_db("planitlo_crm");
  $edit_id = '';
  if(isset($_GET['edit_id']) && $_GET['edit_id']!="")
  {
    $edit_id = $_GET['edit_id'];
    $sql = "SELECT * FROM pdf_invoice WHERE id=".$edit_id;
    $formres = $db->query($sql) or die($sql);
    $formdata = $db->fetchArray($formres);
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CRM</title>
<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
<style type="text/css">
  .pdf_text
  {
    width: 100%;
    border: 0;
    outline: 0;
    border-bottom: 1px solid black;
  }
</style>
<script type="text/javascript" src="js/jquery.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<body>
<? include("header.php"); ?>
<table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td colspan="4" align="left" valign="top" nowrap="nowrap"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="border">
        <tbody>
          <tr>
            <td width="100%" align="left" valign="top" class="lastview">Invoice </td>
          </tr>
          <tr>
            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" valign="top" class="side-nav"><? include('sidenav.php');?></td>
                  <td height="400" align="left" valign="top" class="mar">

                    <form id="form1" name="form1" method="post" action="process_pdf_invoice.php">
                      <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr>
                          <td align="left" valign="top" class="moduleTitle">Create Invoice</td>
                          <td align="right" valign="top">&nbsp;</td>
                        </tr>
          
                      <tr>
                          <td colspan="2" align="left" valign="top"><div id="pidlist">
                              <table width="550" border="0" cellpadding="5" cellspacing="0" class="listViewThS1" style="border: 1px solid #c9c9c9;padding: 9px;">
                                <tr>
                                  <td>
                                    <table width="100%">
                                          <tbody>
                                          <tr>
                                            <td align="center" style="font-size:12px"><img src="images/planit-logo-2.png" height="105px"/><br/><img src="images/planit-logo-3.png" width="270px"/><br/>
                                            Office (818) 376-6506
                                            <br/>
                                            sales@planitlocations.com
                                            <br/>
                                            www.planitlocations.com

                                            </td>
                                          </tr>
                                          <tr>
                                            <td align="center" style="font-size: 18px;color: #000"><br>INVOICE<br></td>
                                          </tr>
                                          </tbody>
                                    </table>

                                    <table cellpadding="5" width="100%">
    <tbody>
      <tr>
        <td width="10%" align="left"><strong>Date: </strong></td>
        <td width="24%"><input name="invoice_date" value="<?php echo isset($formdata) ? $formdata['invoice_date'] : ''; ?>" type="text" class="pdf_text datepick" ></td>
        <td width="19%" align="right"><strong>Invoice Log #</strong> </td>
        <td width="25%"><input name="invoice_log" value="<?php echo isset($formdata) ? $formdata['invoice_log'] : ''; ?>" type="text" class="pdf_text" ></td>
      </tr>
    </tbody>
  </table>

  <table width="100%" cellpadding="5">
    <tbody>
      <tr>
        <td  valign="bottom" width="20%"><strong>Invoice To: </strong></td>
      
        <td width="80%"><input name="invoice_to" value="<?php echo isset($formdata) ? $formdata['invoice_to'] : ''; ?>" type="text" class="pdf_text" ></td>
      </tr>
      <tr>
        <td  valign="bottom" width="25%"><strong>Location Address:</strong></td>
      
        <td width="75%"><input name="location_address" value="<?php echo isset($formdata) ? $formdata['location_address'] : ''; ?>" type="text" class="pdf_text" ></td>
      </tr>
      <tr>
        <td  valign="bottom" width="23%"><strong>Rental Date(s): </strong></td>
      
        <td width="77%"><input name="rental_dates" value="<?php echo isset($formdata) ? $formdata['rental_dates'] : ''; ?>" type="text" class="pdf_text" ></td>
      </tr>
      <tr>
        <td  width="100%" colspan="2"><br><br>Loss/Damage Items, Location fees, Overtime, Cleaning, On-site, Etc.</td>
      </tr>
    </tbody>
  </table>

  <table cellpadding="5" width="100%">
    <tbody>
      <tr>
        <td width="4%" align="left"><strong>$</strong> </td>
        <td width="15%"><input name="amount_1" value="<?php echo isset($formdata) ? $formdata['amount_1'] : ''; ?>" type="text" class="pdf_text" ></td>
        <td width="7%" align="right"><strong>For: </strong></td>
        <td width="74%"><input name="for_1" value="<?php echo isset($formdata) ? $formdata['for_1'] : ''; ?>" type="text" class="pdf_text" ></td>
      </tr>
      <tr>
        <td width="4%" align="left"><strong>$</strong> </td>
        <td width="15%"><input name="amount_2" value="<?php echo isset($formdata) ? $formdata['amount_2'] : ''; ?>" type="text" class="pdf_text" ></td>
        <td width="7%" align="right"><strong>For: </strong></td>
        <td width="74%"><input name="for_2" value="<?php echo isset($formdata) ? $formdata['for_2'] : ''; ?>" type="text" class="pdf_text" ></td>
      </tr>
      <tr>
        <td width="4%" align="left"><strong>$</strong> </td>
        <td width="15%"><input name="amount_3" value="<?php echo isset($formdata) ? $formdata['amount_3'] : ''; ?>" type="text" class="pdf_text" ></td>
        <td width="7%" align="right"><strong>For: </strong></td>
        <td width="74%"><input name="for_3" value="<?php echo isset($formdata) ? $formdata['for_3'] : ''; ?>" type="text" class="pdf_text" ></td>
      </tr>
      <tr>
        <td width="4%" align="left"><strong>$</strong> </td>
        <td width="15%"><input name="amount_4" value="<?php echo isset($formdata) ? $formdata['amount_4'] : ''; ?>" type="text" class="pdf_text" ></td>
        <td width="7%" align="right"><strong>For: </strong></td>
        <td width="74%"><input name="for_4" value="<?php echo isset($formdata) ? $formdata['for_4'] : ''; ?>" type="text" class="pdf_text" ></td>
      </tr>
      <tr>
        <td width="4%" align="left"><strong>$</strong> </td>
        <td width="15%"><input name="amount_5" value="<?php echo isset($formdata) ? $formdata['amount_5'] : ''; ?>" type="text" class="pdf_text" ></td>
        <td width="7%" align="right"><strong>For: </strong></td>
        <td width="74%"><input name="for_5" value="<?php echo isset($formdata) ? $formdata['for_5'] : ''; ?>" type="text" class="pdf_text" ></td>
      </tr>
      <tr>
        <td width="4%" align="left"><strong>$</strong> </td>
        <td width="15%"><input name="amount_6" value="<?php echo isset($formdata) ? $formdata['amount_6'] : ''; ?>" type="text" class="pdf_text" ></td>
        <td width="7%" align="right"><strong>For: </strong></td>
        <td width="74%"><input name="for_6" value="<?php echo isset($formdata) ? $formdata['for_6'] : ''; ?>" type="text" class="pdf_text" ></td>
      </tr>
      <tr>
        <td width="4%" align="left"><strong>$</strong> </td>
        <td width="15%"><input name="amount_7" value="<?php echo isset($formdata) ? $formdata['amount_7'] : ''; ?>" type="text" class="pdf_text" ></td>
        <td width="7%" align="right"><strong>For: </strong></td>
        <td width="74%"><input name="for_7" value="<?php echo isset($formdata) ? $formdata['for_7'] : ''; ?>" type="text" class="pdf_text" ></td>
      </tr>
      <tr>
        <td width="4%" align="left"><strong>$</strong> </td>
        <td width="15%"><input name="amount_8" value="<?php echo isset($formdata) ? $formdata['amount_8'] : ''; ?>" type="text" class="pdf_text" ></td>
        <td width="7%" align="right"><strong>For: </strong></td>
        <td width="74%"><input name="for_8" value="<?php echo isset($formdata) ? $formdata['for_8'] : ''; ?>" type="text" class="pdf_text" ></td>
      </tr>
      <tr>
        <td width="4%" align="left"><strong>$</strong> </td>
        <td width="15%"><input name="amount_9" value="<?php echo isset($formdata) ? $formdata['amount_9'] : ''; ?>" type="text" class="pdf_text" ></td>
        <td width="7%" align="right"><strong>For: </strong></td>
        <td width="74%"><input name="for_9" value="<?php echo isset($formdata) ? $formdata['for_9'] : ''; ?>" type="text" class="pdf_text" ></td>
      </tr>

      
  

    </tbody>
  </table>
  <?php $notesarr = isset($formdata) ? json_decode($formdata['notes']) : array() ; ?>
  <table width="100%" cellpadding="5">
    <tbody>
      <tr>
        <td><br><strong>Notes : </strong></td>
        <td><strong>&nbsp;</strong></td>
      </tr>

      <tr>
        <td colspan="2"><input name="notes[]" value="<?php echo isset($notesarr[0]) ? $notesarr[0] : ''; ?>" type="text" class="pdf_text" ></td>
      </tr>
      <tr>
        <td colspan="2"><input name="notes[]" value="<?php echo isset($notesarr[1]) ? $notesarr[1] : ''; ?>" type="text" class="pdf_text" ></td>
      </tr>

      <tr><td colspan="2"><br><br></td></tr>
      <tr>
        <td><b>Status:</b></td> 
        <td>
          <select name="status" id="status">
            <?php 
              $statuses = array('new','pending','on hold','completed','cancelled');
              foreach ($statuses as $key => $value) {
                $selected = isset($formdata) && $formdata['status'] == $value ? 'selected' : '';
                echo '<option value="'.$value.'" '.$selected.'>'.ucfirst($value).'</option>';
              }
            ?>
          </select>
        </td>
      </tr>
    </tbody>
  </table>
                

  <table cellpadding="5">
    <tr>
      <td><br><br><br><br><input name="submit_btn" type="submit" class="button" value="save" />&nbsp;&nbsp;&nbsp;<button name="submit_btn_2" type="submit" class="button" value="save_create_pdf">Save & Create PDF</button>&nbsp;&nbsp;&nbsp;<input onclick="location.href='pdf_invoice_list.php'" type="button" class="button" value="Cancel" ></td>
    </tr>
  </table>


                           </td></tr>     
                              </table>
                          </div></td>
                        </tr>
                        <tr>
                          <td colspan="2" align="center" valign="top" height="8"></td>
                        </tr>
                        <tr>
                          <td ></td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    </form>
                    <p>&nbsp;</p></td>
                </tr>
            </table></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
  </tbody>
</table>
<script type="text/javascript">
$(".datepick").flatpickr({
  dateFormat: "M d, Y",
});

var getfield=function(div,field)
{  
  var str='';
  str+='<select name="conditions[]">';
  str+='<option value="=">equals</option>';
  str+='<option selected="selected" value="like">contains</option>';
  if(field!='tags')
  {
    str+='<option value="!=">does not equals</option>';
    str+='<option value="<">less then</option>';
    str+='<option value=">">greater then</option>';
  }
  str+='</select>';   
  document.getElementById("conditionsBlock"+div).innerHTML=str; 
}
</script>
<? include("footer.php");?>
</body>
</html>
