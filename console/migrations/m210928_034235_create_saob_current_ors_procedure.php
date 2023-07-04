<?php

use yii\db\Migration;

/**
 * Class m210928_034235_create_saob_current_ors_procedure
 */
class m210928_034235_create_saob_current_ors_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sqlTrigger = <<<SQL
        DROP PROCEDURE IF EXISTS saob_current_ors;
        CREATE PROCEDURE saob_current_ors(from_reporting_period VARCHAR(50),to_reporting_period VARCHAR(50))
        BEGIN 
        SELECT
        saob_ors_total.mfo_code ,
        saob_ors_total.document_recieve,
        saob_ors_total.uacs,
        SUM(saob_ors_total.ors_total) as prev_total
        FROM saob_ors_total
        WHERE
        saob_ors_total.reporting_period >=from_reporting_period
         AND saob_ors_total.reporting_period <=to_reporting_period
        GROUP BY 
        saob_ors_total.mfo_code ,
        saob_ors_total.document_recieve,
        saob_ors_total.uacs;
    
        END
        SQL;

        $this->execute($sqlTrigger);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP PROCEDURE IF EXISTS saob_current_ors")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210928_034235_create_saob_current_ors_procedure cannot be reverted.\n";

        return false;
    }
    */
}
