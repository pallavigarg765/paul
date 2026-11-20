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
	
	<td valign="middle" align="right" style="font-size:12px;text-align:justify"><img src="images/Screenshot_17.png" height="105px" width="182px"/>
	</td>
	<td valign="middle" align="left">
		<div style="font-size:11px">
		<br><br>
		Plan-It Locations, Inc.<br>
		Office 818-376-6506<br>
		sales@planitlocations.com<br>
		www.planitlocations.com<br>
		</div>
	</td>
	</tr>
	<tr >
		<td align="center" colspan="2" style="font-size: 16px;font-weight:900"><br>NON-CIRCUMVENTION AND NON-DISCLOSURE AGREEMENT</td>
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
	//$_POST['recipient'] = 'asda  asd asdasa ';

	$recip_ = str_pad($_POST['recipient'],402,'&nbsp;');
	//$recip_ = str_replace('&nbs','',$recip_);
	if($_POST['recipient']!="")
	{
		$recip_ = $_POST['recipient'];
	}
	$html = '
	<style>
	td{font-size:12px;}

	</style>
	
	<div style="font-size:12px;text-align:justify">1. This Agreement ("Agreement") is entered into by and between Plan-It Locations, Inc and <u style="width:100px">'.$recip_.'</u> ("Recipient") on behalf of Recipient and Recipient\'s principals, partners, agents, employers, employees, and successors (collectively "Recipients"),</div>';

	$html .= '<div style="font-size:12px;text-align:justify">2. In consideration of assistance provided by Plan - It Locations, Inc to Recipients in identifying and/or viewing certain properties ("Properties"), and other good and valuable consideration, the receipt and sufficiency of which is hereby acknowledged, Recipients, and each of them, agree not to enter into any agreement for sale, lease, or use of the Properties, or any of them, unless Plan - It Locations, Inc is acting as the agent of at least one party in the transaction, Recipients, and each of them, further agree not to disclose the address or any other information concerning the Properties, or any of them, to any person or entity, unless such disclosure is required in furtherance of an agreement in which Plan- It Locations, Inc is acting as the agent of at least one party. Recipients\' obligations under this paragraph shall be binding regardless of whether or not Recipients, or any of them had independent knowledge of the Properties, or any of them, prior to receiving assistance from Plan - It Locations, Inc or executing this Agreement.</div>';
	$html .= '<div style="font-size:12px;text-align:justify">3. Recipients acknowledge and agree that among the Properties subject to this Agreement are the following:</div><br>';

	$html .='<table width="100%">
		<tbody>
			<tr>
				<td width="70%" style="border-bottom:1px solid black">'.$_POST["line_1_1"].'</td>
				<td width="5px">&nbsp;</td>
				<td width="15%" style="border-bottom:1px solid black">'.$_POST["line_1_2"].'</td>
			</tr>
			<tr>
				<td width="70%" style="border-bottom:1px solid black">'.$_POST["line_2_1"].'</td>
				<td width="5px">&nbsp;</td>
				<td width="15%" style="border-bottom:1px solid black">'.$_POST["line_2_2"].'</td>
			</tr>
			<tr>
				<td width="70%" style="border-bottom:1px solid black">'.$_POST["line_3_1"].'</td>
				<td width="5px">&nbsp;</td>
				<td width="15%" style="border-bottom:1px solid black">'.$_POST["line_3_2"].'</td>
			</tr>
			<tr>
				<td width="70%" style="border-bottom:1px solid black">'.$_POST["line_4_1"].'</td>
				<td width="5px">&nbsp;</td>
				<td width="15%" style="border-bottom:1px solid black">'.$_POST["line_4_2"].'</td>
			</tr>

		</tbody>
	</table>';

	$html .= '<br><br><div style="font-size:12px;text-align:justify">4. This Agreement and the obligations here under shall survive any negotiations or discussions between the parties to this Agreement and shall terminate five years from the earliest date appearing below.</div>';
	$html .= '<div style="font-size:12px;text-align:justify">5. This Agreement constitutes the entire contract between the parties regarding the subject matter hereof and may only be amended or modified by a writing signed by both parties. Waiver of any provision of this Agreement shall not be deemed or constitute a waiver of any other provision, nor shall such waiver constitute a continuing waiver. If any provision of this Agreement is held invalid, the remaining provisions shall continue in full force and effect. The prevailing party in any action or proceeding brought to enforce or interpret this Agreement shall be entitled to reasonable attorney\'s fees and costs in addition to any other relief granted.</div>';
	$html .= '<div style="font-size:12px;text-align:justify">6. This Agreement may be executed in counterparts, and any facsimile of any party\'s signature shall be deemed to be an original signature for all purposes.</div><br>';

	$html .='<table width="100%">
		<tbody>
			<tr>
				<td width="22%" style="border-bottom:1px solid black">'.$_POST["authority"].'</td>
				<td width="5px">&nbsp;</td>
				<td width="22%" style="border-bottom:1px solid black">'.$_POST["date_1"].'</td>
				<td width="5px">&nbsp;</td>
				<td width="22%" style="border-bottom:1px solid black">'.$_POST["customer"].'</td>
				<td width="5px">&nbsp;</td>
				<td width="22%" style="border-bottom:1px solid black">'.$_POST["date_2"].'</td>
				<td width="5px">&nbsp;</td>
			</tr>
			
			<tr>
				<td width="22%" style="font-size:10px;text-align:center">Ron Jarvis - Agent<br>Plan - It Locations, Inc.</td>
				<td width="5px">&nbsp;</td>
				<td width="22%" style="font-size:10px;text-align:center">Dated</td>
				<td width="5px">&nbsp;</td>
				<td width="22%" style="font-size:10px;text-align:center"></td>
				<td width="5px">&nbsp;</td>
				<td width="22%" style="font-size:10px;text-align:center">Dated</td>
				<td width="5px">&nbsp;</td>
			</tr>
		</tbody>
	</table>';
	
	$pdf->writeHTML($html, true, false, true, false, '');
	// Print text using writeHTMLCell()
	//$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 0, 0, true, '', true);
	//$pdf->Output(dirname(__FILE__).'example_001.pdf', 'F');
	$pdf_name = 'deal-nda-'.date('ymdHis').'.pdf';
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
		$sql = "UPDATE `pdf_nda` SET `line_1_1` = '".addslashes($_POST['line_1_1'])."',`line_1_2` = '".addslashes($_POST['line_1_2'])."',`line_2_1` = '".addslashes($_POST['line_2_1'])."',`line_2_2` = '".addslashes($_POST['line_2_2'])."',`line_3_1` = '".addslashes($_POST['line_3_1'])."',`line_3_2` = '".addslashes($_POST['line_3_2'])."',`line_4_1` = '".addslashes($_POST['line_4_1'])."',`line_4_2` = '".addslashes($_POST['line_4_2'])."',`recipient` = '".addslashes($_POST['recipient'])."',`authority` = '".addslashes($_POST['authority'])."',`date_1` = '".addslashes($_POST['date_1'])."', `customer` = '".addslashes($_POST['customer'])."', `date_2` = '".addslashes($_POST['date_2'])."', `status` = '".$_POST['status']."', `updated_at` = '".$date_to."'";

		if(isset($_POST['submit_btn_2']) && $_POST['submit_btn_2']=='save_create_pdf')
		{
			$sql .= " , pdf_name = '".addslashes($pdf_name)."' ";
		}
		$sql .= " WHERE id = '".$_POST['edit_id']."'";


		$db->query($sql) or die($sql);
		$edit_id = $_POST['edit_id'];
		header('Location: pdf_nda_list.php?success=3');
	}
	else
	{
		$sql = "INSERT INTO `pdf_nda` (`line_1_1`, `line_1_2`, `line_2_1`, `line_2_2`, `line_3_1`, `line_3_2`, `line_4_1`, `line_4_2`, `recipient`, `authority`, `date_1`, `customer`, `date_2`, `pdf_name`, `status`, `created_at`, `updated_at`) VALUES ('".addslashes($_POST['line_1_1'])."', '".addslashes($_POST['line_1_2'])."', '".addslashes($_POST['line_2_1'])."', '".addslashes($_POST['line_2_2'])."', '".addslashes($_POST['line_3_1'])."', '".addslashes($_POST['line_3_2'])."', '".addslashes($_POST['line_4_1'])."', '".addslashes($_POST['line_4_2'])."', '".addslashes($_POST['recipient'])."', '".addslashes($_POST['authority'])."', '".addslashes($_POST['date_1'])."', '".addslashes($_POST['customer'])."', '".addslashes($_POST['date_2'])."','".addslashes($pdf_name)."','".$_POST['status']."','".$date_to."', '".$date_to."')";
		$db->query($sql) or die($sql);
		$edit_id = mysql_insert_id();
		header('Location: pdf_nda_list.php?success=2');
	}
  }