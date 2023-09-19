<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PropertyArticles */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Property Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="property-articles-view card container">


    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary modalButtonUpdate']) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'article_name',
        ],
    ]) ?>

</div>
<style>
    .container {
        padding: 2rem;
    }
</style>
<?php $this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [JqueryAsset::class]]); ?>