<?php

use yii\db\Migration;

/**
 * Class m210928_033213_create_saob_allotment_total_view
 */
class m210928_033213_create_saob_allotment_total_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
        CREATE VIEW saob_allotment_total as 
            SELECT
            record_allotments_view.mfo_code ,
            record_allotments_view.document_recieve,
            record_allotments_view.uacs,
            SUM(record_allotments_view.amount)  total_allotment
            FROM record_allotments_view
            GROUP BY record_allotments_view.mfo_code ,
            record_allotments_view.document_recieve,
            record_allotments_view.uacs
        SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS saob_allotment_total")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210928_033213_create_saob_allotment_total_view cannot be reverted.\n";

        return false;
    }
    */
}
