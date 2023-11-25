<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\date\DatePicker;
use yii\helpers\Url;

$js = '

function calculation(counter)
{
	quantity = $("#purchaseitems-"+counter+"-quantity").val();
	if(quantity == "")
		quantity = 1;
	quantity = parseFloat(quantity);	
		
	price = $("#purchaseitems-"+counter+"-price").val();
	price = parseFloat(price);
	total = quantity * price;
		

	$("#purchaseitems-"+counter+"-total").val(total.toFixed(2));	

	
	/* CALCULATION FOR ALL TOTAL COLUMNS STARTS */
	subTotal = 0;
	$(".totalLinePrice").each(function(){
		if($(this).val() != "")
			subTotal += parseFloat($(this).val());
	});	
	

	
	taxTotal = 0;

	netTotal = subTotal + taxTotal;
	$("#purchases-subtotal").val(subTotal.toFixed(2));	


	$("#purchases-nettotal").val(netTotal.toFixed(2));	
	
	/* CALCULATION FOR ALL TOTAL COLUMNS ENDS */
}

jQuery(document).on("focus",".autocomplete_product",function(){
		
		id_string = $(this).attr("id");
	  	id_array = id_string.split("-");
	  	counter = id_array[1];
	  	
		$(this).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : "'.Url::to(['products/search']).'",
				dataType: "json",
				method: "GET",
				data: {
				   term: request.term,
				   action: "purchase"
				},
				 success: function( data ) {
					if(data.length === 0)
					{
						$("#purchaseitems-"+counter+"-productnameuser").val("");
					}
					
				    response( $.map( data, function( item ) {
				    return {
							label: item.label,
							value: item.label,
							data : item
					  }
					}));
				  }
			});
		},
		autoFocus: true,	      	
		minLength: 0,
		select: function( event, ui ) {
		    $("#purchaseitems-"+counter+"-productid").val(ui.item.data.id);
		    $("#purchaseitems-"+counter+"-productname").val(ui.item.data.productName);
		    $("#purchaseitems-"+counter+"-sno").val(ui.item.data.sno);
		    $("#purchaseitems-"+counter+"-hsncode").val(ui.item.data.hsnCode);
		    $("#purchaseitems-"+counter+"-price").val(ui.item.data.price);
		    $("#purchaseitems-"+counter+"-per").val(ui.item.data.per);
		    $("#purchaseitems-"+counter+"-quantity").val(1);
		    $("#purchaseitems-"+counter+"-cgstper").val(ui.item.data.cgstPer);
		    $("#purchaseitems-"+counter+"-sgstper").val(ui.item.data.sgstPer);
		    $("#purchaseitems-"+counter+"-igstper").val(ui.item.data.igstPer);
		    $("#purchaseitems-"+counter+"-brand").val(ui.item.data.brand);
		    $("#purchaseitems-"+counter+"-model").val(ui.item.data.model);
		    calculation(counter);
		}		      	
	});
});

jQuery(document).on("focus",".autocomplete_supplier",function(){
			  	
		$(this).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : "'.Url::to(['customers/search']).'",
				dataType: "json",
				method: "GET",
				data: {
				   term: request.term
				},
				 success: function( data ) {
					if(data.length === 0)
					{
						$("#purchases-suppliername").val("");
					} 
				    response( $.map( data, function( item ) {
				    return {
							label: item.label,
							value: item.label,
							data : item
					  }
					}));
				  }
			});
		},
		autoFocus: true,	      	
		minLength: 0,
		select: function( event, ui ) {
		    $("#purchases-supplieraddress").val(ui.item.data.customerAddress); 
			$("#purchases-suppliergstin").val(ui.item.data.customerGstin); 
		}		      	
	});
});

$(document).on("change keyup blur",".changesMade",function(){
	id_string = $(this).attr("id");
	id_array = id_string.split("-");
	counter = id_array[1];
	calculation(counter);
});

$(document).on("click",".toggleTopBox",function(){
	$(".top_boxes").slideToggle("slow");
});


';

$this->registerJs($js);
?>

	
		
<div class="purchase-form">
<div class="box box-default">
<div class="box-body">
    <?php $form = ActiveForm::begin(['id' => 'purchase-form']); ?>
    <div class="row">
		<div class='col-lg-4'>
			<div class='col-lg-12'>
				<div class="form-group">
					
					<?php 
					echo '<label>Purchase Date</label>';
					echo DatePicker::widget([
						'model' => $modelPurchase, 
						'attribute' => 'purchaseDate',
						'readonly' => true,
						'removeButton' => false,
						'type' => DatePicker::TYPE_COMPONENT_APPEND,
						'options' => ['placeholder' => 'Enter purchase date ...'],
						'pluginOptions' => [
							'todayHighlight' => true,
							 'format' => 'dd/mm/yyyy'
						]
					]);
							
					?>
				</div>
			</div>	

			<div class='col-lg-6'>
				<div class="form-group">
					<?= $form->field($modelPurchase, 'status')->dropDownList(['0' => 'Not Paid', '1' => 'Paid'],['prompt'=>'Select Option']) ?>		
				</div>
			</div>	
		</div>
	     <div class='col-lg-4'>
			<div class="form-group">
				<?= $form->field($modelPurchase, 'supplierName')->textInput(['maxlength' => true,'class'=>'form-control autocomplete_supplier','placeholder'=>'Find by supplier name']) ?>
				
			</div>
			<div class="form-group">
				<?= $form->field($modelPurchase, 'supplierGstin')->textInput(['maxlength' => true]) ?>
			</div>
		</div>
	    <div class='col-lg-4'>
			<div class="form-group">
				<?= $form->field($modelPurchase, 'supplierAddress')->textArea(['rows' => 4]) ?>
			</div>
		</div>
		
				
				
    </div>
     
             <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                'limit' => 20, // the maximum times, an element can be cloned (default 999)
                'min' => 1, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $modelPurchaseItems[0],
                'formId' => 'purchase-form',
                'formFields' => [
                    'productName',
                    'sno',
                    'hsnCode',
                    'per',
                    'price',
                    'cgstper',
                    'sgstper',
                    'igstper',
                    'brand',
                    'model',
                ],
            ]); ?>
			<div class="box-body table-responsive no-padding">
              <table class="table text-center">
              	<tr>
				  	
                  <th width="12%">Product</th>
                  <th width="10%">SNO</th>
                  <!-- <th width="10%">HSN/SAC</th> -->
                  <th width="10%">Price</th>
                  <th width="6%">Qty</th>
                  <th width="6%">Per</th>
                  <th width="9%">Total</th>
                 <!--  <th width="6%">Cgst %</th>
                  <th width="6%">Sgst %</th>
                  <th width="6%">Igst %</th>
                  <th width="9%">Tax</th> -->
                  <th width="10%"></th>
				  
                </tr>
             <tbody class="container-items"> 	
             <?php foreach ($modelPurchaseItems as $i => $modelPurchaseItem): ?>
			
				<tr class="item">
				  
                 <td>
						
						<?= $form->field($modelPurchaseItem, "[{$i}]productNameUser")->textInput(['maxlength' => true,'class'=>'form-control autocomplete_product','placeholder'=>'Name or Code'])->label(false); ?>
                  </td>
                  
                  <td>
						<?= $form->field($modelPurchaseItem, "[{$i}]sno")->textInput(['maxlength' => true,'class'=>'form-control'])->label(false); ?>
				  </td>

                  <td>
						<?= $form->field($modelPurchaseItem, "[{$i}]price")->textInput(['maxlength' => true,'class'=>'form-control changesMade'])->label(false); ?>
				  </td>
				  <td>
						<?= $form->field($modelPurchaseItem, "[{$i}]quantity")->textInput(['maxlength' => true,'class'=>'form-control changesMade'])->label(false); ?>
					</td>

					
					<td>
						<?= $form->field($modelPurchaseItem, "[{$i}]total")->textInput(['readonly' => true,'class' => 'form-control totalLinePrice'])->label(false); ?>
					</td>
																	  
      
					<td>
						<button type="button" class="add-item btn btn-success"><i class="glyphicon glyphicon-plus"></i></button>
                        <button type="button" class="remove-item btn btn-danger"><i class="glyphicon glyphicon-minus"></i></button>
						<?php
                            // necessary for update action.
                            if (! $modelPurchaseItem->isNewRecord) {
                                echo Html::activeHiddenInput($modelPurchaseItem, "[{$i}]id");
                            }
                        ?>
                        
                            <?= $form->field($modelPurchaseItem, "[{$i}]productName")->hiddenInput(['class'=>''])->label(false); ?>
                            	
				  </td>
                </tr>
	                    
            <?php endforeach; ?>
			</tbody></table>
            
            </div>
            <?php DynamicFormWidget::end(); ?>
	
      <div class="row">
			
			
			
			<div class='col-md-3'>
				<?= $form->field($modelPurchase, 'subTotal')->textInput(['maxlength' => true,'readOnly'=> true]) ?>
				
			</div>
			<div class='col-md-3'>
				<?= $form->field($modelPurchase, 'netTotal')->textInput(['maxlength' => true,'readOnly'=> true]) ?>
				
			</div>
	</div>
     
	<hr> 
	
		
   
	
   
	
    <div class="form-group">
        <?= Html::submitButton($modelPurchaseItem->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
       
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
