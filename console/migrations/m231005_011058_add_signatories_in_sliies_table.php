<?php

use yii\db\Migration;

/**
 * Class m231005_011058_add_signatories_in_sliies_table
 */
class m231005_011058_add_signatories_in_sliies_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sliies', 'fk_certified_correct_by', $this->bigInteger());
        $this->createIndex('idx-sliies-fk_certified_correct_by', 'sliies', 'fk_certified_correct_by');
        $this->addForeignKey('fk-sliies-fk_certified_correct_by', 'sliies', 'fk_certified_correct_by', 'employee', 'employee_id', 'RESTRICT', 'CASCADE');

        $this->addColumn('sliies', 'fk_approved_by', $this->bigInteger());
        $this->createIndex('idx-sliies-fk_approved_by', 'sliies', 'fk_approved_by');
        $this->addForeignKey('fk-sliies-fk_approved_by', 'sliies', 'fk_approved_by', 'employee', 'employee_id', 'RESTRICT', 'CASCADE');

        $this->addColumn('sliies', 'fk_accounting_head', $this->bigInteger());
        $this->createIndex('idx-sliies-fk_accounting_head', 'sliies', 'fk_accounting_head');
        $this->addForeignKey('fk-sliies-fk_accounting_head', 'sliies', 'fk_accounting_head', 'employee', 'employee_id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-sliies-fk_certified_correct_by', 'sliies');
        $this->dropIndex('idx-sliies-fk_certified_correct_by', 'sliies');
        $this->dropColumn('sliies', 'fk_certified_correct_by');

        $this->dropForeignKey('fk-sliies-fk_approved_by', 'sliies',);
        $this->dropIndex('idx-sliies-fk_approved_by', 'sliies');
        $this->dropColumn('sliies', 'fk_approved_by');

        $this->dropForeignKey('fk-sliies-fk_accounting_head', 'sliies');
        $this->dropIndex('idx-sliies-fk_accounting_head', 'sliies');
        $this->dropColumn('sliies', 'fk_accounting_head');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231005_011058_add_signatories_in_sliies_table cannot be reverted.\n";

        return false;
    }
    */
}
