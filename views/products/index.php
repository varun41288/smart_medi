<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\Helper;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	<div class="box box-default">
	<div class="box-body">
    <p>
        <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'productName:ntext',
            'productCode:ntext',
            'sno:ntext',
            //'per:ntext',
            [
            'attribute'  => 'price',
            'format'  => 'html',
            'value'  => function ($data) {
                 return Helper::amount_to_money($data->price);
            },
           
			],
            /*[
            'attribute'  => 'cgstPer',
            'format'  => 'html',
            'value'  => function ($data) {
                 return ($data->cgstPer."%");
            },
           
			],
            [
            'attribute'  => 'sgstPer',
            'format'  => 'html',
            'value'  => function ($data) {
                 return ($data->sgstPer."%");
            },
           
			],
           */
            'stock',
            [
                'attribute' => 'brand',
                'filter'    => [""=>'All',"Own Brand"=>"Own Brand","For Rent"=>"For Rent"],
				'value'  => function ($data) {
					return ($data->brand == 'Own Brand') ? "Own Brand" : (($data->brand == 'For Rent') ? "For Rent" : "Not Set");
				},
            ], 
            //'model:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
	</div>
	</div>
</div>
