<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $model app\models\Invoices */

$this->title = "Invoice No : ".$model->invoiceNo;
$this->params['breadcrumbs'][] = ['label' => 'Sales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$box5_title = "";
if(!empty($model->userAttributes[0]->value) && !empty($model->userAttributes[0]->key))
	if($model->userAttributes[0]->key=='box5_title')
		$box5_title = $model->userAttributes[0]->value;

$box5_content = "";
if(!empty($model->userAttributes[1]->value) && !empty($model->userAttributes[1]->key))
	if($model->userAttributes[1]->key=='box5_content')
		$box5_content = $model->userAttributes[1]->value;	
	


?>

<style>
table {
    border-collapse: collapse;
}
table, td, th {
    border: 1px solid black;
    padding:5px;
    vertical-align:top;
}
.items td {border-bottom:none;border-top:none;height:10px;}
.total_items td {border-bottom:none;font-weight:bold;}
.total_items_style td {font-weight:bold;}
.topnone {border-top:none !important;}
.right {text-align:right !important;}
p {text-align:center;}
.title {font-weight:bold;}
.title span {font-weight:normal;}

</style>

<div class="invoices-view">
<div class="box box-default">
<div class="box-body">
     <p>
        <?= Html::a('Print', ['export', 'id' => $model->id], ['class' => 'btn btn-success','target' => '_blank']) ?>
       
    </p>
<p class="title">TAX INVOICE<br><span> </span></p>
<table width="100%" style="table-layout: fixed;">
	<tr>
		<td width="50%" rowspan="2" colspan="3" class="address">
			
		<?php if($user_settings->value1=='yes'): ?>
			<div style="float:left;height:120px;padding-top:10px;padding-right:5px;"><img src="<?= Yii::getAlias('@web') ?>/logo.png" width="100" alt=""/></div>
    		<div><b><?php echo $profile->company_name; ?></b><br>
			<?php echo nl2br($profile->address);	?></div>
		<?php else: ?>
		
			<b><?php echo $profile->company_name; ?></b><br>
			<?php echo nl2br($profile->address);	?>

		<?php endif; ?>	



		</td>
		<td width="25%" style="word-wrap:break-word">Invoice No <br><b><?php echo $model->invoiceNo; ?></b></td>
		<td width="25%" style="word-wrap:break-word">Dated <br><b><?php echo $model->invoiceDate; ?></b></td>
	</tr>	
	<tr>
		<td width="25%" style="word-wrap:break-word"><b>Customer</b><br><?php echo $model->customerName; ?></td>
		<td width="25%" style="word-wrap:break-word"><b>Customer Address</b><br><?php echo nl2br($model->customerAddress);	?><br>
			PH-NO : <?php echo $model->customerGstin; ?></td>
	</tr>	
</table>		
<table width="100%" class="item-table">		
	<tr class="items">
		<th class="topnone" width="5%">SNO</th>
		<th class="topnone" width="50%">Description of Goods</th>
		<th class="topnone" width="8%">Unit</th>
		<th class="topnone" width="5%">Rent Days</th>
		<th class="topnone" width="10%">Price</th>
		<th class="topnone" width="10%">Amount</th>
	</tr>
	<?php foreach($model->invoiceItems as $key => $invoiceItem): 
	
	@$tax_bottom_box[$invoiceItem->cgstPer." %"]['total'] += ($invoiceItem->quantity * $invoiceItem->price); 
	@$tax_bottom_box[$invoiceItem->cgstPer." %"]['cgstTot'] += ($invoiceItem->quantity * $invoiceItem->price)*($invoiceItem->cgstPer/100); 
	@$tax_bottom_box[$invoiceItem->sgstPer." %"]['sgstTot'] += ($invoiceItem->quantity * $invoiceItem->price)*($invoiceItem->sgstPer/100);
	@$tax_bottom_box[$invoiceItem->igstPer==""?0:$invoiceItem->igstPer." %"]['igstTot'] += ($invoiceItem->quantity * $invoiceItem->price)*($invoiceItem->igstPer/100); 
	
	?>
	<tr class="items">
		<td style="text-align:center;"><?php echo ($key+1); ?></td>
		<td class="left"><?php 
			  echo $invoiceItem->productName; 
			  if(!empty($invoiceItem->sno)) 
				  // echo "<br>SNO:".$invoiceItem->sno;
			?></td>
		<td><?php echo $invoiceItem->quantity." ".$invoiceItem->per; ?></td>
		<td style="text-align:right;"><?php echo ($invoiceItem->sno); ?></td>
		<td style="text-align:right;"><?php echo Helper::amount_to_money($invoiceItem->price); ?></td>
		<td style="text-align:right;"><?php echo Helper::amount_to_money($invoiceItem->quantity * $invoiceItem->price); ?></td>
		</tr>
	<?php endforeach; 
	
	//~ echo "<pre>";
	//~ print_r($tax_bottom_box);
	//~ exit;
	
	?>
	<tr class="total_items total_items_style">
		<td></td>
		<td class="right"></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;"><?php echo Helper::amount_to_money($model->subTotal); ?></td>
	</tr>
	<?php if($model->roundOff!=""): ?>
	<tr class="items total_items_style">
		<td></td>
		<td class="right">Round Off </td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;"><?php echo Helper::amount_to_money($model->roundOff); ?></td>
	</tr>
	<?php endif; ?>	
	<?php if($model->discount!=""): ?>
	<tr class="items total_items_style">
		<td></td>
		<td class="right">Discount (-) </td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;"><?php echo Helper::amount_to_money($model->discount); ?></td>
	</tr>
	<?php endif; ?>	
	
	
	
	 
	<tr class="total_items total_items_style">
		<td></td>
		<td class="right"> TOTAL (Rs.) </td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:right;"><?php echo Helper::amount_to_money($model->netTotal); ?></td>
	</tr>	
	<tr class="total_items total_items_style">
		<td colspan="7" class="left"><span style="font-weight:normal;">Amount Chargeable (in words)</span> <?php echo Helper::decimal_to_words($model->netTotal); ?></td>
	</tr>	
	
</table>

<table width="100%" class="item-table" cellspacing="0" cellpadding="0">		
	<tr class="tax_items total_items_style reduce_height">
		<td rowspan="2">Transportation Details</td>
		<td colspan="2" style="word-wrap:break-word"><?php echo $model->box1_title; ?></td>
		<td colspan="2" style="word-wrap:break-word"><?php echo $model->box2_title; ?></td>
		<td colspan="2" style="word-wrap:break-word"><?php echo $model->box3_title; ?></td>
		<td colspan="2" style="word-wrap:break-word"><?php echo $model->box4_title; ?></td>
		<td style="word-wrap:break-word"><?php echo $box5_title; ?></td>
	</tr>	
	<tr class="tax_items reduce_height">
		<td colspan="2" ><?php echo $model->box1_content; ?></td>
		<td colspan="2"><?php echo $model->box2_content; ?></td>
		<td colspan="2" ><?php echo $model->box3_content; ?></td>
		<td colspan="2"><?php echo $model->box4_content; ?></td>
		<td colspan="2" >Rs.<?php echo Helper::amount_to_money($box5_content); ?></td>
		
	</tr>
		
	<tr class="total_items total_items_style">
		<td colspan="7" class="left"><span style="font-weight:normal;">Tax Amount (in words)</span> <?php echo Helper::decimal_to_words($box5_content); ?></td>
	</tr>	
</table>
<table width="100%">	
	<tr class="">
		
		<td width="50%" colspan="4" style="font-weight:bold;">Receiver Sign with Seal<br><br>&nbsp;</td>
		<td width="50%" class="left"><span><u>Bank details :</u></span> <br> <span><?php echo $user_settings->value3; ?></span></td>
	</tr>	
		
	
</table>
<table>	
	<tr class="">
		<td width="50%" class="left"><span style="font-weight:bold;"><u>Declaration :</u></span> <br> <?php echo $user_settings->value6; ?></td>
		<td width="50%" colspan="4" class="right">For <?php echo $profile->company_name; ?><br><br><br><span style="font-weight:bold;">Authorized Signatory</span> </td>
	</tr>	
		
	
</table>
<p>This is a computer generated invoice.</p>

</div>
</div>
</div>

