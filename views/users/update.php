<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = 'Settings';

?>
<div class="users-update">

   
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
