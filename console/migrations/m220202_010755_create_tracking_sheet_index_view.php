<?php

use yii\db\Migration;

/**
 * Class m220202_010755_create_tracking_sheet_index_view
 */
class m220202_010755_create_tracking_sheet_index_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            CREATE VIEW tracking_sheet_index AS 
                SELECT 
                dv_aucs.id,
                dv_aucs.particular,
                dv_aucs.dv_number,
                payee.account_name,
                dv_aucs.recieved_at
                FROM `dv_aucs`
                LEFT JOIN payee on dv_aucs.payee_id = payee.id
                ORDER BY dv_aucs.created_at DESC


        SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS tracking_sheet_index")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220202_010755_create_tracking_sheet_index_view cannot be reverted.\n";

        return false;
    }
    */
}
