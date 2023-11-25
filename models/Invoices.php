<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoices".
 *
 * @property int $id
 * @property int $invoiceNo
 * @property string $invoiceDate
 * @property double $cgstTotal
 * @property double $sgstTotal
 * @property double $igstTotal
 * @property double $subTotal
 * @property double $taxTotal
 * @property double $netTotal
 * @property int $customerID
 */
class Invoices extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $box5_title;
	public $box5_content;
	
    public static function tableName()
    {
        return 'invoices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoiceNo','invoiceDate', 'subTotal', 'netTotal','customerName','customerAddress','customerGstin'], 'required'],
            [['invoiceDate','discount','roundOff'], 'safe'],
            //[['cgstTotal', 'sgstTotal', 'igstTotal', 'subTotal', 'taxTotal', 'netTotal'], 'number'],
            [['subTotal', 'netTotal'], 'number'],
            [['customerName','customerAddress','customerGstin'], 'string'],
            [['box1_title','box2_title','box3_title','box4_title','box5_title'], 'string'],
            [['box1_content','box2_content','box3_content','box4_content','box5_content'], 'string'],
			[['discount'], 'number', 'numberPattern' => '/^\s*[0-9]+(\.[0-9][0-9]?)?\s*$/','message' => 'Not a valid discount price.'],
			[['roundOff'], 'number', 'numberPattern' => '/^-?[0-9]\d*(\.\d+)?$/','message' => 'Not a valid roundOff price.'],
            ['cgstTotal', 'default', 'value' => 0],
            ['sgstTotal', 'default', 'value' => 0],
            ['igstTotal', 'default', 'value' => 0],
            ['taxTotal', 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invoiceNo' => 'Invoice No',
            'invoiceDate' => 'Invoice Date',
            'cgstTotal' => 'CGST Total',
            'sgstTotal' => 'SGST Total',
            'igstTotal' => 'IGST Total',
            'subTotal' => 'Sub Total',
            'taxTotal' => 'Tax Total',
            'netTotal' => 'Net Total',
            'customerName' => 'Customer Name',
			'customerAddress' => 'Customer Address',
			'customerGstin' => 'Customer Mobile',
            'discount' => 'Discount',
            'roundOff' => 'Round Off',
            'box1_title' => 'Box 1 Title',
            'box2_title' => 'Box 2 Title',
            'box3_title' => 'Box 3 Title',
            'box4_title' => 'Box 4 Title',
            'box5_title' => 'Box 5 Title',
			'box1_content' => 'Box 1 Content',
			'box2_content' => 'Box 2 Content',
			'box3_content' => 'Box 3 Content',
			'box4_content' => 'Box 4 Content',
            'box5_content' => 'Box 5 Content',
            'price' => 'Rent Per Item',
            'sno' => 'Rent Days',
            
        ];
    }
     public function getInvoiceItems()
    {
		return $this->hasMany(InvoiceItems::className(), ['invoiceID' => 'id']);
	}
	
	public function getUserAttributes()
    {
		return $this->hasMany(UserAttributes::className(), ['reference' => 'id']);
	}
     
}
