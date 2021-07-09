<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PoResponsibilityCenter */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Po Responsibility Centers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="po-responsibility-center-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::button(' Update', ['value' => Url::to(yii::$app->request->baseUrl .
            '/index.php?r=po-responsibility-center/update&id=' . $model->id), 'id' => 'modalButtoncreate', 'class' => 'btn btn-primary', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'description:ntext',
        ],
    ]) ?>

</div>

<?php
$script = <<<JS
        $('#modalButtoncreate').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
JS;
$this->registerJs($script)
?>