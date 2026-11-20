<?php
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");
ini_set('memory_limit', '-1');
require_once dirname( __FILE__ ) . '/tcpdf_min/tcpdf.php';
include('include/security.php');
include('include/dbcon.php');
include('include/config.php');
include('include/functions.php');
if(isset($_POST))
{
	$pdf_name = '';
	if(isset($_POST['submit_btn_2']) && $_POST['submit_btn_2']=='save_create_pdf')
	{
	$html = '
	<style>
	table{
	padding-rop: 15px;
	}
	
	</style>
	<br><br>
	<table >
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
		<td align="center" style="font-size: 23px;font-weight:900"><br><br>INVOICE</td>
	</tr>
	</tbody>
	</table>
	';
	//end content
	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	// set default monospaced font
	// set margins
	$pdf->SetMargins(25,5,25);
	// remove default header/footer
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	// set default font subsetting mode
	$pdf->setFontSubsetting(true);
	// Set font
	// dejavusans is a UTF-8 Unicode font, if you only need to
	// print standard ASCII chars, you can use core fonts like
	// helvetica or times to reduce file size.
	
	$pdf->AddPage();

	$pdf->writeHTML($html, true, false, true, false, '');


	$html = '
	<style>
	table{
		padding-rop: 15px;
	}
	td{font-size:14px;}

	</style>
	<table cellpadding="5" width="100%">
		<tbody>
			<tr>
				<td width="10%" align="left">Date: </td>
				<td width="24%" style="border-bottom:1px solid black">'.$_POST["invoice_date"].'</td>
				<td width="19%" align="right">Invoice Log # </td>
				<td width="25%" style="border-bottom:1px solid black">'.$_POST["invoice_log"].'</td>
			</tr>
		</tbody>
	</table>

	<table width="100%" cellpadding="5">
		<tbody>
			<tr>
				<td  valign="bottom" width="20%">Invoice To: </td>
			
				<td width="80%" style="border-bottom:1px solid black">'.$_POST["invoice_to"].'</td>
			</tr>
			<tr>
				<td  valign="bottom" width="25%">Location Address:</td>
			
				<td width="75%" style="border-bottom:1px solid black">'.$_POST["location_address"].'</td>
			</tr>
			<tr>
				<td  valign="bottom" width="23%">Rental Date(s): </td>
			
				<td width="77%" style="border-bottom:1px solid black">'.$_POST["rental_dates"].'</td>
			</tr>
			<tr>
				<td  width="100%" colspan="2"><br><br>Loss/Damage Items, Location fees, Overtime, Cleaning, On-site, Etc.</td>
			</tr>
		</tbody>
	</table>

	<table cellpadding="5" width="100%">
		<tbody>
			<tr>
				<td width="4%" align="left">$ </td>
				<td width="15%" style="border-bottom:1px solid black">'.$_POST["amount_1"].'</td>
				<td width="7%" align="right">For: </td>
				<td width="74%" style="border-bottom:1px solid black">'.$_POST["for_1"].'</td>
			</tr>
			<tr>
				<td width="4%" align="left">$ </td>
				<td width="15%" style="border-bottom:1px solid black">'.$_POST["amount_2"].'</td>
				<td width="7%" align="right">For: </td>
				<td width="74%" style="border-bottom:1px solid black">'.$_POST["for_2"].'</td>
			</tr>
			<tr>
				<td width="4%" align="left">$ </td>
				<td width="15%" style="border-bottom:1px solid black">'.$_POST["amount_3"].'</td>
				<td width="7%" align="right">For: </td>
				<td width="74%" style="border-bottom:1px solid black">'.$_POST["for_3"].'</td>
			</tr>
			<tr>
				<td width="4%" align="left">$ </td>
				<td width="15%" style="border-bottom:1px solid black">'.$_POST["amount_4"].'</td>
				<td width="7%" align="right">For: </td>
				<td width="74%" style="border-bottom:1px solid black">'.$_POST["for_4"].'</td>
			</tr>
			<tr>
				<td width="4%" align="left">$ </td>
				<td width="15%" style="border-bottom:1px solid black">'.$_POST["amount_5"].'</td>
				<td width="7%" align="right">For: </td>
				<td width="74%" style="border-bottom:1px solid black">'.$_POST["for_5"].'</td>
			</tr>
			<tr>
				<td width="4%" align="left">$ </td>
				<td width="15%" style="border-bottom:1px solid black">'.$_POST["amount_6"].'</td>
				<td width="7%" align="right">For: </td>
				<td width="74%" style="border-bottom:1px solid black">'.$_POST["for_6"].'</td>
			</tr>
			<tr>
				<td width="4%" align="left">$ </td>
				<td width="15%" style="border-bottom:1px solid black">'.$_POST["amount_7"].'</td>
				<td width="7%" align="right">For: </td>
				<td width="74%" style="border-bottom:1px solid black">'.$_POST["for_7"].'</td>
			</tr>
			<tr>
				<td width="4%" align="left">$ </td>
				<td width="15%" style="border-bottom:1px solid black">'.$_POST["amount_8"].'</td>
				<td width="7%" align="right">For: </td>
				<td width="74%" style="border-bottom:1px solid black">'.$_POST["for_8"].'</td>
			</tr>
			<tr>
				<td width="4%" align="left">$ </td>
				<td width="15%" style="border-bottom:1px solid black">'.$_POST["amount_9"].'</td>
				<td width="7%" align="right">For: </td>
				<td width="74%" style="border-bottom:1px solid black">'.$_POST["for_9"].'</td>
			</tr>
		</tbody>
	</table>


	<table width="100%" cellpadding="5">
	    <tbody>
	      <tr>
	        <td><br><br>Notes :</td>
	      </tr>

	      <tr>
	        <td style="border-bottom:1px solid black">'.$_POST['notes'][0].'</td>
	      </tr>
	      <tr>
	        <td style="border-bottom:1px solid black">'.$_POST['notes'][1].'</td>
	      </tr>

	    </tbody>
	  </table>


	';
	// echo $html;
	// exit;
	$pdf->writeHTML($html, true, false, true, false, '');
	// Print text using writeHTMLCell()
	//$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 0, 0, true, '', true);
	//$pdf->Output(dirname(__FILE__).'example_001.pdf', 'F');
	$pdf_name = 'invoice-'.date('ymdHis').'.pdf';
	//$pdf_name = 'test.pdf';
	$pdf->Output(dirname(__FILE__).'/pdfs/'.$pdf_name, 'F');
	//$pdf->Output($pdf_name, 'F');
	//$pdf->Output(dirname(__FILE__).'/pdfs/'.$pdf_name.'', 'FD');
	}
	$date_to = date('Y-m-d H:i:s');

	$db = new DBConn();
	mysql_select_db("planitlo_crm");
	if(isset($_POST['edit_id']) && $_POST['edit_id']!='')
	{
		$notes = json_encode($_POST['notes']);
		$sql = "UPDATE `pdf_invoice` SET invoice_date = '".addslashes($_POST['invoice_date'])."' , invoice_log = '".addslashes($_POST['invoice_log'])."' , invoice_to = '".addslashes($_POST['invoice_to'])."' , location_address = '".addslashes($_POST['location_address'])."' , rental_dates = '".addslashes($_POST['rental_dates'])."' , amount_1 = '".addslashes($_POST['amount_1'])."' , for_1 = '".addslashes($_POST['for_1'])."' , amount_2 = '".addslashes($_POST['amount_2'])."' , for_2 = '".addslashes($_POST['for_2'])."' , amount_3 = '".addslashes($_POST['amount_3'])."' , for_3 = '".addslashes($_POST['for_3'])."' , amount_4 = '".addslashes($_POST['amount_4'])."' , for_4 = '".addslashes($_POST['for_4'])."' , amount_5 = '".addslashes($_POST['amount_5'])."' , for_5 = '".addslashes($_POST['for_5'])."' , amount_6 = '".addslashes($_POST['amount_6'])."' , for_6 = '".addslashes($_POST['for_6'])."' , amount_7 = '".addslashes($_POST['amount_7'])."' , for_7 = '".addslashes($_POST['for_7'])."', notes = '".addslashes($notes)."', `status` = '".$_POST['status']."', updated_at = '".$date_to."' ";
		if(isset($_POST['submit_btn_2']) && $_POST['submit_btn_2']=='save_create_pdf')
		{
			$sql .= " , pdf_name = '".$pdf_name."' ";
		}
		$sql .= " WHERE id = '".$_POST['edit_id']."'";
		$db->query($sql) or die($sql);
		$edit_id = $_POST['edit_id'];
		header('Location: pdf_invoice_list.php?success=3');
	}
	else
	{
		$notes = json_encode($_POST['notes']);
		$sql = "INSERT INTO `pdf_invoice` (`invoice_date`, `invoice_log`, `invoice_to`, `location_address`, `rental_dates`, `amount_1`, `for_1`, `amount_2`, `for_2`, `amount_3`, `for_3`, `amount_4`, `for_4`, `amount_5`, `for_5`, `amount_6`, `for_6`, `amount_7`, `for_7`, `notes`, `pdf_name`,`status`, `created_at`, `updated_at`) VALUES ('".addslashes($_POST['invoice_date'])."', '".addslashes($_POST['invoice_log'])."', '".addslashes($_POST['invoice_to'])."', '".addslashes($_POST['location_address'])."', '".addslashes($_POST['rental_dates'])."', '".addslashes($_POST['amount_1'])."', '".addslashes($_POST['for_1'])."', '".addslashes($_POST['amount_2'])."', '".addslashes($_POST['for_2'])."', '".addslashes($_POST['amount_3'])."', '".addslashes($_POST['for_3'])."', '".addslashes($_POST['amount_4'])."', '".addslashes($_POST['for_4'])."', '".addslashes($_POST['amount_5'])."', '".addslashes($_POST['for_5'])."', '".addslashes($_POST['amount_6'])."', '".addslashes($_POST['for_6'])."', '".addslashes($_POST['amount_7'])."', '".addslashes($_POST['for_7'])."', '".addslashes($notes)."',  '".$pdf_name."','".$_POST['status']."', '".$date_to."', '".$date_to."')";
		$db->query($sql) or die($sql);
		$edit_id = mysql_insert_id();
		header('Location: pdf_invoice_list.php?success=2');
	}
	
}