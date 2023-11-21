<?php

use yii\db\Migration;

/**
 * Class m231120_012503_add_fk_payee_id_in_due_diligence_reports_table
 */
class m231120_012503_add_fk_payee_id_in_due_diligence_reports_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('due_diligence_reports', 'fk_payee_id', $this->bigInteger());
        $this->createIndex('idx-due_diligence_reports-fk_payee_id', 'due_diligence_reports', 'fk_payee_id');
        $this->addForeignKey('fk-due_diligence_reports-fk_payee_id', 'due_diligence_reports', 'fk_payee_id', 'payee', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-due_diligence_reports-fk_payee_id', 'due_diligence_reports');
        $this->dropIndex('idx-due_diligence_reports-fk_payee_id', 'due_diligence_reports');
        $this->dropColumn('due_diligence_reports', 'fk_payee_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231120_012503_add_fk_payee_id_in_due_diligence_reports_table cannot be reverted.\n";

        return false;
    }
    */
}
