<?php

use app\components\Helper;

echo printInvoice($model,$profile,$user_settings,"Original");
echo '<div class="page-break"></div>';
echo printInvoice($model,$profile,$user_settings,"Copy");
	
function printInvoice($model,$profile,$user_settings,$title)
{
	
$content = '<p style="text-align:center;font-size:14px;font-weight:bold;">TAX INVOICE <span style="font-size:12px;font-weight:normal;">('.$title.')</span></p>
<table width="100%" style="table-layout: fixed;">
	<tr>
		<td width="50%" rowspan="2" colspan="3" class="address">';

if($user_settings->value1=='yes')
{			
	$content .= '<table style="border:none;"><tr><td style="border:none;vertical-align:middle;"><img src="'.Yii::getAlias('@web').'/logo.png" width="70" alt=""/></td>
				<td style="border:none;font-size:12px;"><b>'.$profile->company_name.'</b><br>
				<span style="font-size:12px;">'.nl2br($profile->address).'</span></td></tr></table>';
}
else
{
	$content .= '<span style="font-size:12px;"><b>'.$profile->company_name.'</b></span><br><span style="font-size:12px;">
			'.nl2br($profile->address).'</span>';
}

$box5_title = "";
if(!empty($model->userAttributes[0]->value) && !empty($model->userAttributes[0]->key))
	if($model->userAttributes[0]->key=='box5_title')
		$box5_title = $model->userAttributes[0]->value;

$box5_content = "";
if(!empty($model->userAttributes[1]->value) && !empty($model->userAttributes[1]->key))
	if($model->userAttributes[1]->key=='box5_content')
		$box5_content = $model->userAttributes[1]->value;	
	

$content .='
		</td>
		<td width="25%" style="word-wrap:break-word;font-size:12px;">Invoice No : <span style="font-size:12px;"><b>'.$model->invoiceNo.'</b></span></td>
		<td width="25%" style="word-wrap:break-word;font-size:12px;">Dated : <span style="font-size:12px;"><b>'.$model->invoiceDate.'</b></span></td>
	</tr>	

	<tr>
		<td width="25%" style="word-wrap:break-word"><b>Customer</b><br>'.$model->customerName.'</td>
		<td width="25%" style="word-wrap:break-word"><b>Customer Address</b><br>'.$model->customerAddress.'<br>
			PH-NO : '.$model->customerGstin.'</td>
	</tr>	
	
</table>		
<table width="100%" class="item-table">		
	<tr class="items">
	<th class="topnone" width="10%" style="text-align:center;font-size:12px;">SNO</th>
	<th class="topnone" width="50%" style="text-align:center;font-size:12px;">Description of Goods</th>
	<th class="topnone" width="8%" style="text-align:center;font-size:12px;">Unit</th>
	<th class="topnone" width="14%" style="text-align:center;font-size:12px;">Rent Days</th>
	<th class="topnone" width="8%" style="text-align:center;font-size:12px;">Price</th>
	<th class="topnone" width="10%" style="text-align:center;font-size:12px;">Amount</th>
	</tr>';
	foreach($model->invoiceItems as $key => $invoiceItem) { 
	
	
	$sno = "";
	if(!empty($invoiceItem->sno))
	{
		$sno = "<br>".$invoiceItem->sno;
	}		
	
	$content .='	
	<tr class="items">
		<td style="text-align:center;font-size:12px;">'.($key+1).'</td>
		<td class="left" style="font-size:12px;">'.$invoiceItem->productName.' </td>
		<td style="font-size:12px;">'.$invoiceItem->quantity." ".$invoiceItem->per.'</td>
		<td style="text-align:center;">'.$invoiceItem->sno.'</td>
		<td style="text-align:center;font-size:12px;">'.Helper::amount_to_money($invoiceItem->price).'</td>
		<td style="text-align:center;font-size:12px;">'.Helper::amount_to_money($invoiceItem->quantity * $invoiceItem->price).'</td>
		</tr>';
	}
	
	for($i=count($model->invoiceItems);$i<11;$i++){
	$content .='	
	<tr class="items">
		<td style="text-align:center;font-size:12px;">&nbsp;</td>
		<td class="left" style="font-size:12px;">&nbsp;</td>
		<td style="text-align:right;font-size:12px;">&nbsp;</td>
		<td style="text-align:right;font-size:12px;">&nbsp;</td>
		<td style="text-align:right;font-size:12px;">&nbsp;</td>
		<td></td>
		</tr>';
	}

	
	$content .='
	<tr class="total_items total_items_style">
		<td style="border-bottom:none;border-top:none;"></td>
		<td style="border-bottom:none;border-top:none;"></td>
		<td style="border-bottom:none;border-top:none;"></td>
		<td style="border-bottom:none;border-top:none;"></td>
		<td></td>
		<td style="text-align:right;font-size:12px;">'.Helper::amount_to_money($model->subTotal).'</td>
	</tr>';
	if($model->roundOff!="") {
	$content .='<tr class="items total_items_style">
		<td></td>
		<td class="right" style="font-size:12px;">Round Off </td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;font-size:12px;">'.Helper::amount_to_money($model->roundOff).'</td>
	</tr>';
    }
	if($model->discount!="") {
	$content .='<tr class="items total_items_style">
		<td></td>
		<td class="right" style="font-size:12px;">Discount (-) </td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;font-size:12px;">'.Helper::amount_to_money($model->discount).'</td>
	</tr>';
    }
	
	
	$content .=' 
	<tr class="total_items total_items_style">
		<td></td>
		<td class="right" style="font-size:12px;">TOTAL (Rs.) </td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;font-size:12px;">'.Helper::amount_to_money($model->netTotal).'</td>
	</tr>	
	<tr class="total_items total_items_style">
		<td colspan="7" class="left" style="font-size:10px;"><span style="font-weight:normal;font-size:12px;">Amount Chargeable (in words)</span> '.Helper::decimal_to_words($model->netTotal).'</td>
	</tr>	
	
</table>
<table width="100%" class="item-table" cellspacing="0" cellpadding="0">		
	<tr class="tax_items total_items_style reduce_height">
		<td rowspan="2" colspan="2" style="text-align:center">Transportation Details</td>
		<td colspan="2" style="word-wrap:break-word;font-size:12px;">'.$model->box1_title.'</td>
		<td colspan="2" style="word-wrap:break-word;font-size:12px;">'.$model->box2_title.'</td>
		<td colspan="2" style="word-wrap:break-word;font-size:12px;">'.$model->box3_title.'</td>
		<td colspan="2" style="word-wrap:break-word;font-size:12px;">'.$model->box4_title.'</td>
		<td colspan="2" style="word-wrap:break-word;font-size:12px;">'.$box5_title.'</td>
	</tr>	
	<tr class="tax_items reduce_height">
		<td colspan="2" style="font-size:12px;">'.$model->box1_content.'</td>
		<td colspan="2" style="font-size:12px;">'.$model->box2_content.'</td>
		<td colspan="2" style="font-size:12px;">'.$model->box3_content.'</td>
		<td colspan="2" style="font-size:12px;">'.$model->box4_content.'</td>
		<td colspan="2" style="font-size:12px;">'.Helper::amount_to_money($box5_content).'</td>
		
	</tr>
		
	<tr class="total_items total_items_style">
		<td colspan="7" class="left"><span style="font-weight:normal;">Tax Amount (in words)</span> '.Helper::decimal_to_words($box5_content).'</td>
	</tr>	
</table>

<table width="100%">	
	<tr class="">
		
		<td width="50%" colspan="4" style="font-size:10px;font-weight:bold;">Receiver Sign with Seal<br><br>&nbsp;</td>
		<td width="50%" class="left" style="font-size:12px;"><span style="font-size:10px;"><u>Bank details :</u></span> <br> <span style="font-size:10px;">'.$user_settings->value3.'</span></td>
	</tr>	
		
	
</table>
<table>	
	<tr class="">
		<td width="50%" class="left" style="font-size:12px;"><span style="font-weight:bold;font-size:12px;"><u>Declaration :</u></span> <br> <span style="font-size:10px;">'.$user_settings->value6.'</span></td>
		<td width="50%" colspan="4" style="font-size:12px;" class="right">For '.$profile->company_name.'<br><br><br><span style="font-weight:bold;font-size:12px;">Authorized Signatory</span> </td>
	</tr>	
		
	
</table>

<p style="font-size:10px;">This is a computer generated invoice.</p>';
return $content; 
}