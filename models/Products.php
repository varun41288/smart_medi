<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $productName
 * @property string $hsnCode
 * @property string $per
 * @property double $price
 * @property int $cgstPer
 * @property int $sgstPer
 * @property int $igstPer
 * @property string $brand
 * @property string $model
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productCode','productName', 'price', 'sno'], 'required'],
            [['productName','sno', 'hsnCode', 'per', 'brand', 'model'], 'string'],
            [['productName'], 'unique'],
            [['productCode'], 'unique'],
            //[['hsnCode'], 'unique'],
            [['price'], 'number', 'numberPattern' => '/^\s*[0-9]+(\.[0-9][0-9]?)?\s*$/','message' => 'Not a valid Price.'], 
            [['sno'], 'number', 'numberPattern' => '/^\s*[0-9]+(\.[0-9][0-9]?)?\s*$/','message' => 'Not a valid Count.'],
            [['cgstPer'], 'number', 'min' => 0,'max' => 100,'message' => 'Not a valid Percentage.'],
            [['sgstPer'], 'number', 'min' => 0,'max' => 100,'message' => 'Not a valid Percentage.'],
            [['igstPer'], 'number', 'min' => 0,'max' => 100,'message' => 'Not a valid Percentage.'],
            ['per', 'default', 'value' => 0],
            ['hsnCode', 'default', 'value' => 0],
             ['cgstPer', 'default', 'value' => 0],
             ['sgstPer', 'default', 'value' => 0],
             ['igstPer', 'default', 'value' => 0],
             ['stock', 'default', 'value' => 0],
             ['opening_stock', 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'productName' => 'Product Name',
            'hsnCode' => 'Serial Number',
            'per' => 'Per',
            'price' => 'Rent Per Item',
            'cgstPer' => 'CGST',
            'sgstPer' => 'SGST',
            'igstPer' => 'IGST',
            'brand' => 'Brand',
            'model' => 'Rent From',
			'expiryDate' => 'Expiry Date',
            'sno' => 'Rent Period in Days',
            'stock' => 'Stock in Hand'
        ];
    }
}
