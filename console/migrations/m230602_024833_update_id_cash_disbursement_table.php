<?php

use yii\db\Migration;

/**
 * Class m230602_024833_update_id_cash_disbursement_table
 */
class m230602_024833_update_id_cash_disbursement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 0")->query();
        $this->alterColumn('cash_disbursement', 'id', $this->bigInteger());
        $this->alterColumn('cash_disbursement', 'parent_disbursement', $this->bigInteger());
        $this->alterColumn('acics_cash_items', 'fk_cash_disbursement_id', $this->bigInteger());
        $this->alterColumn('advances_entries', 'cash_disbursement_id', $this->bigInteger());
        $this->alterColumn('cash_disbursement_items', 'fk_cash_disbursement_id', $this->bigInteger());
        $this->alterColumn('jev_preparation', 'cash_disbursement_id', $this->bigInteger());
        $this->alterColumn('lddap_adas', 'fk_cash_disbursement_id', $this->bigInteger());
        $this->alterColumn('sliies', 'fk_cash_disbursement_id', $this->bigInteger());
        $this->alterColumn('acic_cancelled_items', 'fk_cash_disbursement_id', $this->bigInteger());
        $this->alterColumn('transmittal_entries', 'cash_disbursement_id', $this->bigInteger());
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
        echo "m230602_024833_update_id_cash_disbursement_table cannot be reverted.\n";

        return false;
    }
    */
}
