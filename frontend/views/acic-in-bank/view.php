<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AcicInBank */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Acic In Banks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="acic-in-bank-view card container">


    <p>

        <?= Yii::$app->user->can('update_acic_in_bank') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'mdModal btn btn-primary']) : '' ?>

    </p>
    <table class="table" id="items_tbl">
        <thead>
            <th>ACIC No.</th>
        </thead>
        <tbody>
            <?php

            foreach ($items as $itm) {
                echo "<tr>
                    <td>{$itm['serial_number']}</td>
                </tr>";
            }

            ?>


        </tbody>
    </table>

</div>
<style>
    .acic-in-bank-view {
        padding: 3rem;
    }
</style>