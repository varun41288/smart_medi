<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Modules */

$this->title = 'Create Modules';
$this->params['breadcrumbs'][] = ['label' => 'Modules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modules-create">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
