<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SubAccounts2 */

$this->title = 'Create Sub Accounts2';
$this->params['breadcrumbs'][] = ['label' => 'Sub Accounts2s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-accounts2-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
        // 'id'=>$id
    ]) ?>

</div>
