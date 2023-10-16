<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UnitOfMeasure */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Unit Of Measures', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="unit-of-measure-view">


    <div class="container card" style="padding: 1rem;">
        <p>
            <?= Yii::$app->user->can('update_unit_of_measure') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'modalButtonUpdate btn btn-primary']) : '' ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'unit_of_measure',
            ],
        ]) ?>

    </div>
</div>
<?php

$this->registerJsFile(
    '@web/frontend/web/js/globalFunctions.js',
    [
        'depends' => [JqueryAsset::class]
    ]
);

?>