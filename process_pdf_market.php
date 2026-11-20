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
		<td align="center" style="font-size: 18px;font-weight:900"><br><br>WE WOULD LOVE TO MARKET YOUR PROPERTY!</td>
	</tr>
	<tr>
		<td align="center" style="font-size: 12px;">FOR POSSIBLE FILM, TELEVISION, VIDEO, PHOTOGRAPHY, EVENTS, MUSIC VIDEOS!</td>
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
	// print stamarketrd ASCII chars, you can use core fonts like
	// helvetica or times to reduce file size.
	
	$pdf->AddPage();

	$pdf->writeHTML($html, true, false, true, false, '');
	//$_POST['recipient'] = 'asda  asd asdasa ';

	$recip_ = str_pad($_POST['recipient'],402,'&nbsp;');
	//$recip_ = str_replace('&nbs','',$recip_);
	if($_POST['recipient']!="")
	{
		$recip_ = $_POST['recipient'];
	}
	$html 	= '
	<style>
	td{font-size:12px;}</style><div style="font-size:12px;text-align:justify"><u>Here\'s a Little About us:</u><br>Plan-It Locations, Inc has many successful locations in the greater Los Angeles area. We enjoy working with Property Owner\'s like you. We introduce your Property to film producers, photographers, event organizers, and television production companies that may be interested in using your Property as a film location. We register properties on a non-exclusive basis.</div>';

	$html .= '<div style="font-size:12px;text-align:justify"><u>Here\'s How It Works:</u><br>If a marketed property is selected for a film project, photography, an event, or TV show, Plan-It Locations, Inc will provide a legal Agreement, inclusive of your rights as Owner, and the rights of those who may rent your property. The Agreement reflects all the terms, i.e. rental fee(s), and additional fees, such as electrical, telephone, pool-heating, etc.) the dates of rental, what the requirements for the project may be, and what you may require as a property owner. When both parties have agreed to all terms, the "Contract" is signed and your earnings are 75% of the total location rental fee. Plan-It Locations, Inc acts as "the Agent" between you and the "Producer" renting your Property for filming. Plan-It Locations, Inc earns 30% of the total location rental fee - making it a 75/25 split. Marketed and selected for rental over a period of months, your earnings could be considerable. <br><br>If you have interest in working with us, Plan-It Locations, Inc will professionally photograph your Property and market the photographs for the filming industry for renting your Property as a film location in their future projects. Our "marketing" is done through E-Mail, Internet, Website and from the photographs we would house in our extensive Location Library. <br> <br>We hope you will consider our proposal regarding your Property, and we look forward to the possibility of making new friends, as well as a successful and profitable year ahead! <br><br>Please visit our website to see our past projects.<br>Yes! As the Owner of the Property located at the following address:</div>';


	$html .='<table width="100%">
		<tbody>
			<tr>
				<td width="15%">Full Address:</td>
				<td width="5px">&nbsp;</td>
				<td width="80%" style="border-bottom:1px solid black">'.$_POST["address"].'</td>
			</tr>
			<tr>
				<td width="15%">Phone Number:</td>
				<td width="5px">&nbsp;</td>
				<td width="80%" style="border-bottom:1px solid black">'.$_POST["phone_no"].'</td>
			</tr>

		</tbody>
	</table>';

	$html .= '<br><br><div style="font-size:12px;text-align:justify">I am giving my permission to Plan-It Locations, Inc to photograph and market my Property by signing below:</div><br>';

	$html .='<table width="100%">
		<tbody>
			<tr>
				<td width="30%" style="border-bottom:1px solid black">'.$_POST["property_owner"].'</td>
				<td width="5px">&nbsp;</td>
				<td width="30%" style="border-bottom:1px solid black">'.$_POST["print_name"].'</td>
				<td width="5px">&nbsp;</td>
				<td width="30%" style="border-bottom:1px solid black">'.$_POST["date"].'</td>
			</tr>
			<tr>
				<td width="30%" style="text-align:center">(Property Owner)</td>
				<td width="5px">&nbsp;</td>
				<td width="30%" style="text-align:center">(Print Name):</td>
				<td width="5px">&nbsp;</td>
				<td width="30%" style="text-align:center">Date:</td>
			</tr>
		</tbody>
	</table>';
	
	$pdf->writeHTML($html, true, false, true, false, '');
	// Print text using writeHTMLCell()
	//$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 0, 0, true, '', true);
	//$pdf->Output(dirname(__FILE__).'example_001.pdf', 'F');
	$pdf_name = 'deal-market-'.date('ymdHis').'.pdf';
	//$pdf_name = 'test.pdf'; ax
	$pdf->Output(dirname(__FILE__).'/pdfs/'.$pdf_name, 'F');
	}
	
	//$pdf->Output($pdf_name, 'F');
	//$pdf->Output(dirname(__FILE__).'/pdfs/'.$pdf_name.'', 'FD');
	$date_to = date('Y-m-d H:i:s');
	


	$db = new DBConn();
	mysql_select_db("planitlo_crm");
	if(isset($_POST['edit_id']) && $_POST['edit_id']!='')
	{
		$sql = "UPDATE `pdf_market` SET `address` = '".addslashes($_POST['address'])."',`phone_no` = '".addslashes($_POST['phone_no'])."',`property_owner` = '".addslashes($_POST['property_owner'])."',`print_name` = '".addslashes($_POST['print_name'])."',`date` = '".addslashes($_POST['date'])."', `status` = '".$_POST['status']."', `updated_at` = '".$date_to."'";

		if(isset($_POST['submit_btn_2']) && $_POST['submit_btn_2']=='save_create_pdf')
		{
			$sql .= " , pdf_name = '".addslashes($pdf_name)."' ";
		}
		$sql .= " WHERE id = '".$_POST['edit_id']."'";


		$db->query($sql) or die($sql);
		$edit_id = $_POST['edit_id'];
		header('Location: pdf_market_list.php?success=3');
	}
	else
	{
		$sql = "INSERT INTO `pdf_market` (`address`, `phone_no`, `property_owner`, `print_name`, `date`, `pdf_name`, `status`, `created_at`, `updated_at`) VALUES ('".addslashes($_POST['address'])."', '".addslashes($_POST['phone_no'])."', '".addslashes($_POST['property_owner'])."', '".addslashes($_POST['print_name'])."', '".addslashes($_POST['date'])."','".addslashes($pdf_name)."','".$_POST['status']."','".$date_to."', '".$date_to."')";
		$db->query($sql) or die($sql);
		$edit_id = mysql_insert_id();
		header('Location: pdf_market_list.php?success=2');
	}
  }