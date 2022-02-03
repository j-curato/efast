<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\BankAccount */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bank Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bank-account-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::button('<i class="glyphicon glyphicon-plus"></i> update', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=bank-account/update&id=' . $model->id), 'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'account_number',
            'province',
            'created_at',
        ],
    ]) ?>

</div>