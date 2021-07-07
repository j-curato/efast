<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EventSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="event-index">


    <div style="height:400px;width:400px">
        <?= edofre\fullcalendar\Fullcalendar::widget([
            'events' => $events
        ]);
        ?>
    </div>
    <?php
    //  \yii2fullcalendar\yii2fullcalendar::widget([
    //     'events' => $events,
    // ]);
    ?>

</div>
