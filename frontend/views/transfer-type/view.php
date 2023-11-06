<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TransferType */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Transfer Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="transfer-type-view container card">


    <p>
        <?= Yii::$app->user->can('update_transfer_type') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'mdModal btn btn-primary']) : '' ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'type',
        ],
    ]) ?>

</div>
<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => JqueryAsset::class])
?>
<style>
    .container {
        padding: 3rem;
    }
</style>