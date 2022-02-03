<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CheckRange */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Check Ranges', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="check-range-view">


    <p>
        <?= Html::button('<i class="glyphicon glyphicon-pencil"></i> Update', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=check-range/update&id=' . $model->id), 'id' => 'modalButtoncreate', 'class' => 'btn btn-primary', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'from',
            'to',
            'reporting_period',
            'begin_balance',
            'bankAccount.account_number'
        ],
    ]) ?>

</div>