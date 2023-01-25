<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%temp_liquidation}}`.
 */
class m230125_021246_create_temp_liquidation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        YIi::$app->db->createCommand("CREATE TABLE temp_liquidation LIKE liquidation")->execute();
        YIi::$app->db->createCommand("INSERT INTO temp_liquidation SELECT * FROM liquidation")->execute();
        $this->addColumn('liquidation', 'temp_dv_number', $this->string());
        Yii::$app->db->createCommand("UPDATE liquidation SET liquidation.temp_dv_number = liquidation.dv_number ")->execute();
        Yii::$app->db->createCommand("UPDATE liquidation SET liquidation.dv_number  = NULL
        WHERE 
        liquidation.dv_number IN (
        (SELECT
        l.dv_number
        FROM 
        (SELECT 
        liquidation.dv_number,
        COUNT(liquidation.dv_number)  as q
        FROM `liquidation` 
        
        
        GROUP BY liquidation.dv_number  
        ORDER BY `q`  DESC) as l
        WHERE l.q >1)
        )")->execute();
        $this->createIndex('idx-dv_number', 'liquidation', 'dv_number', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%temp_liquidation}}');
        $this->dropIndex('idx-dv_number', 'liquidation');
    }
}
