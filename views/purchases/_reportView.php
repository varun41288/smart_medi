<?php

use app\components\Helper;

echo printPurchase($model,$profile,"Original");
echo '<div class="page-break"></div>';
echo printPurchase($model,$profile,"Copy");
	
function printPurchase($model,$profile,$title)
{
	$status = ($model->status == 0) ? "Not Paid" : (($model->status == 1) ? "Paid" : "Partial Paid");
$content = '<p class="title">TAX PURCHASE ('.$title.') <br><span> </span></p>
<table width="100%" style="table-layout: fixed;">
	
	<tr>
		<td width="70%" >
			<b>Return From Customer</b><br>
		</td>
		<td width="30%">
			<b>Return Date : </b>'.$model->purchaseDate.'
		</td>
	</tr>
	<tr>
		<td width="70%" >
			'.$model->supplierName.' <br>
			'.$model->supplierAddress.'<br>
			PHNO : '.$model->supplierGstin.'
		</td>
		<td width="30%">
		<b>Payment status : </b>'.$status.'
		
		</td>
		</tr>

	
</table>		
<table width="100%" class="item-table">		
	<tr class="items">
		<th class="topnone" width="5%">SNO</th>
		<th class="topnone" width="65%">Description of Goods</th>
		<th class="topnone" width="10%">Return Items QTY</th>
		<th class="topnone" width="10%">Price</th>
		<th class="topnone" width="10%">Amount</th>
	</tr>';
	foreach($model->purchaseItems as $key => $purchaseItem) { 
	
	@$tax_bottom_box[$purchaseItem->cgstPer]['total'] += ($purchaseItem->quantity * $purchaseItem->price); 
	
	$sno = "";
	if(!empty($purchaseItem->sno))
	{
		$sno = $purchaseItem->sno." - "; 
		$sno .= $purchaseItem->productName; 
		
	}
	
	$content .='	
	<tr class="items">
		<td style="text-align:center;">'.($key+1).'</td>
		<td class="left">'.$sno.'</td>
		<td>'.$purchaseItem->quantity." ".$purchaseItem->per.'</td>
		<td style="text-align:right;">'.Helper::amount_to_money($purchaseItem->price).'</td>
		<td style="text-align:right;">'.Helper::amount_to_money($purchaseItem->quantity * $purchaseItem->price).'</td>
		</tr>';
	}
	
	for($i=count($model->purchaseItems);$i<8;$i++){
	$content .='	
	<tr class="items">
		<td style="text-align:center;">&nbsp;</td>
		<td class="left">&nbsp;</td>
		<td style="text-align:center;">&nbsp;</td>
		<td>&nbsp;</td>
		<td style="text-align:right;">&nbsp;</td>
		</tr>';
	}

	
	$content .='
	<tr class="total_items total_items_style">
		<td></td>
		<td class="right"></td>
		<td></td>
		<td></td>
		<td style="text-align:right;">'.Helper::amount_to_money($model->subTotal).'</td>
	</tr>';
	
	
	
   	
	$content .=' 
	<tr class="total_items total_items_style">
		<td></td>
		<td class="right">TOTAL (Rs.) </td>
		<td></td>
		<td></td>
		<td style="text-align:right;">'.Helper::amount_to_money($model->netTotal).'</td>
	</tr>	
	<tr class="total_items total_items_style">
		<td colspan="7" class="left"><span style="font-weight:normal;">Amount Chargeable (in words)</span> '.Helper::decimal_to_words($model->netTotal).'</td>
	</tr>	
	
</table>


<table>	
	<tr class="">
		<td width="50%" class="left"><span style="font-weight:bold;"><u>Declaration :</u></span> <br> We declare that this Returned item shows the actual rent price of the goods described and that all particulars are true and correct.</td>
		<td width="50%" colspan="4" class="right">For '.$profile->company_name.'<br><br><br><span style="font-weight:bold;">Authorized Signatory</span> </td>
	</tr>	
		
	
</table>
<p>This is a computer generated Returns bill.</p>';
return $content; 
}
