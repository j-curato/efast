<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PoTransaction */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Po Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="po-transaction-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    <p>
        <?= Html::button('Update', [
            'value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=po-transaction/update&id=' . $model->id),
            'id' => 'modalButtoncreate', 'class' => 'btn btn-primary', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector'
        ]); ?>
    </p>
    <?= Html::a('Delete', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Are you sure you want to delete this item?',
            'method' => 'post',
        ],
    ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'responsibility_center_id',
            'payee:ntext',
            'particular:ntext',
            'amount',
            'payroll_number',
        ],
    ]) ?>

</div>

<?php
$script = <<<JS
  
         $('#modalButtoncreate').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
        $('.modalButtonedit').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });

JS;
$this->registerJs($script);
?>