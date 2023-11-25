<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice_items".
 *
 * @property int $id
 * @property int $invoiceID
 * @property int $productID
 * @property int $quantity
 */
class InvoiceItems extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    
    public $cgstTot; 
    public $sgstTot; 
    public $igstTot; 
    public $total; 
    public $tax; 
    public $stock; 
    public $productNameUser; 
     
    public static function tableName()
    {
        return 'invoice_items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price'], 'required'],
            [['invoiceID'], 'integer'],
            [['productName','sno', 'hsnCode', 'per', 'brand', 'model', 'total', 'tax', 'cgstTot', 'sgstTot', 'igstTot', 'stock'], 'string'],
            [['price'], 'number', 'numberPattern' => '/^\s*[0-9]+(\.[0-9][0-9]?)?\s*$/','message' => 'Not a valid Price.'],
            [['quantity'], 'number', 'numberPattern' => '/^\s*[0-9]+(\.[0-9][0-9][0-9]?)?\s*$/','message' => 'Not a valid quantity.'],
            [['quantity'], 'number', 'min' => 0.1,'message' => 'Not a valid Quantity.'],
            [['cgstPer'], 'number', 'min' => 0,'max' => 100,'message' => 'Not a valid Percentage.'],
            [['sgstPer'], 'number', 'min' => 0,'max' => 100,'message' => 'Not a valid Percentage.'],
            [['igstPer'], 'number', 'min' => 0,'max' => 100,'message' => 'Not a valid Percentage.'],
            
            [['productNameUser'], 'required', 'message' => 'Product name is required.'],
            ['hsnCode', 'default', 'value' => 0],
             ['cgstPer', 'default', 'value' => 0],
             ['sgstPer', 'default', 'value' => 0],
             ['igstPer', 'default', 'value' => 0],
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoiceID' => 'Invoice ID',
            'productID' => 'No',
            'quantity' => 'Quantity',
            'cgstPer' => 'CGST',
            'sgstPer' => 'SGST',
            'igstPer' => 'IGST',
            'per' => 'Per',
            'brand' => 'Brand',
            'model' => 'Model',
            'total' => 'Total',
            'tax' => 'Tax',
            'sno' => 'SNO',
        ];
    }
    
    
}
