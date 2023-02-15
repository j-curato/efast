<?php

use yii\db\Migration;

/**
 * Class m230215_020215_update_process_ors_index_view
 */
class m230215_020215_update_process_ors_index_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS process_ors_index;
        CREATE VIEW process_ors_index as 
        SELECT 
         
        process_ors.id,
        process_ors.serial_number,
        process_ors.reporting_period,
        process_ors.date,
        `transaction`.tracking_number,
        `transaction`.particular,
        responsibility_center.`name` as r_center,
        payee.account_name as payee,
process_ors.type
        FROM 
        process_ors
        LEFT JOIN `transaction` ON process_ors.transaction_id = `transaction`.id
        LEFT JOIN payee ON `transaction`.payee_id = payee.id
        LEFT JOIN responsibility_center ON `transaction`.responsibility_center_id = responsibility_center.id")
            ->queryAll();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
     
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230215_020215_update_process_ors_index_view cannot be reverted.\n";

        return false;
    }
    */
}
