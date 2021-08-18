<?php

use yii\db\Migration;

/**
 * Class m210711_092752_create_conso_dv_all_procedure
 */
class m210816_004003_create_conso_dv_all_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        Yii::$app->db->createCommand("DROP PROCEDURE IF EXISTS conso_dv_all")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210711_092752_create_conso_dv_all_procedure cannot be reverted.\n";

        return false;
    }
    */
}
