<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SubAccounts2 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Sub Accounts2s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sub-accounts2-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::button('Update', [
            'value' => Url::to(Yii::$app->request->baseUrl . '?r=sub-accounts2/update&id=' . $model->id),
            'class' => 'btn btn-primary modalButtoncreate'
        ]) ?>
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
            'sub_accounts1_id',
            'object_code',
            'name',
            [
                'attribute' => 'is_active',
                'value' => function ($model) {

                    return $model->is_active === 1 ? 'True' : 'False';
                }
            ],
        ],
    ]) ?>
</div>
<?php
$script = <<<JS
    $('.modalButtoncreate').click(function(){
        $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'))
    });

JS;
$this->registerJs($script);
?>