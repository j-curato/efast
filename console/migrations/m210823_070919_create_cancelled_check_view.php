<?php

use yii\db\Migration;

/**
 * Class m210823_070919_create_cancelled_check_view
 */
class m210823_070919_create_cancelled_check_view extends Migration
{
    /**
     * {@inheritdoc}
     * 
     */
    public function safeUp()
    {
        $sql = <<< SQL
            CREATE VIEW cancelled_checks_view as 
            SELECT
            liquidation.id,
            liquidation.province,
            liquidation.reporting_period,
            liquidation.check_date,
            liquidation.check_number,
            check_range.`from`,
            check_range.`to`,
            liquidation.payee



            FROM
            liquidation
            LEFT JOIN check_range ON liquidation.check_range_id = check_range.id

            WHERE
            liquidation.payee LIKE 'cancelled%'
            ORDER BY liquidation.created_at DESC

        SQL;

        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand('DROP VIEW cancelled_checks_view')->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210823_070919_create_cancelled_check_view cannot be reverted.\n";

        return false;
    }
    */
}
