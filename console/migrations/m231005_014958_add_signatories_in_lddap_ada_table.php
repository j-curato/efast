<?php

use yii\db\Migration;

/**
 * Class m231005_014958_add_signatories_in_lddap_ada_table
 */
class m231005_014958_add_signatories_in_lddap_ada_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('lddap_adas', 'fk_certified_correct_by', $this->bigInteger());
        $this->createIndex('idx-lddap_adas-fk_certified_correct_by', 'lddap_adas', 'fk_certified_correct_by');
        $this->addForeignKey('fk-lddap_adas-fk_certified_correct_by', 'lddap_adas', 'fk_certified_correct_by', 'employee', 'employee_id', 'RESTRICT', 'CASCADE');

        $this->addColumn('lddap_adas', 'fk_approved_by', $this->bigInteger());
        $this->createIndex('idx-lddap_adas-fk_approved_by', 'lddap_adas', 'fk_approved_by');
        $this->addForeignKey('fk-lddap_adas-fk_approved_by', 'lddap_adas', 'fk_approved_by', 'employee', 'employee_id', 'RESTRICT', 'CASCADE');

        $this->addColumn('lddap_adas', 'fk_accounting_head', $this->bigInteger());
        $this->createIndex('idx-lddap_adas-fk_accounting_head', 'lddap_adas', 'fk_accounting_head');
        $this->addForeignKey('fk-lddap_adas-fk_accounting_head', 'lddap_adas', 'fk_accounting_head', 'employee', 'employee_id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-lddap_adas-fk_certified_correct_by', 'lddap_adas');
        $this->dropIndex('idx-lddap_adas-fk_certified_correct_by', 'lddap_adas');
        $this->dropColumn('lddap_adas', 'fk_certified_correct_by');

        $this->dropForeignKey('fk-lddap_adas-fk_approved_by', 'lddap_adas',);
        $this->dropIndex('idx-lddap_adas-fk_approved_by', 'lddap_adas');
        $this->dropColumn('lddap_adas', 'fk_approved_by');

        $this->dropForeignKey('fk-lddap_adas-fk_accounting_head', 'lddap_adas');
        $this->dropIndex('idx-lddap_adas-fk_accounting_head', 'lddap_adas');
        $this->dropColumn('lddap_adas', 'fk_accounting_head');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231005_014958_add_signatories_in_lddap_ada_table cannot be reverted.\n";

        return false;
    }
    */
}
