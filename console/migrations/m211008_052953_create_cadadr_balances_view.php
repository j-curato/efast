<?php

use yii\db\Migration;

/**
 * Class m211008_052953_create_cadadr_balances_view
 */
class m211008_052953_create_cadadr_balances_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // $sql= <<<SQL
        // CREATE VIEW cadadr_balances as 
        // SELECT
        // cadadr.book_name,
        // reporting_period,
        // SUM(nca_recieve) as total_nca_recieve,
        // SUM(check_issued) as total_check_issued,
        // SUM(ada_issued) as total_ada_issued
        // FROM cadadr
        // GROUP BY
        // cadadr.book_name,
        // reporting_period 
        // SQL;
        // $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      Yii::$app->db->createCommand("DROP VIEW IF EXISTS cadadr_balances")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211008_052953_create_cadadr_balances_view cannot be reverted.\n";

        return false;
    }
    */
}
