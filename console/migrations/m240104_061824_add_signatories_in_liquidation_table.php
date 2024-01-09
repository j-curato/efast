<?php

use yii\db\Migration;

/**
 * Class m240104_061824_add_signatories_in_liquidation_table
 */
class m240104_061824_add_signatories_in_liquidation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('liquidation', 'fk_certified_by', $this->bigInteger());
        $this->createIndex('idx-liquidation-fk_certified_by', 'liquidation', 'fk_certified_by');
        $this->addForeignKey(
            'fk-liquidation-fk_certified_by',
            'liquidation',
            'fk_certified_by',
            'employee',
            'employee_id',
            'RESTRICT'
        );
        $this->addColumn('liquidation', 'fk_approved_by', $this->bigInteger());
        $this->createIndex('idx-liquidation-fk_approved_by', 'liquidation', 'fk_approved_by');
        $this->addForeignKey(
            'fk-liquidation-fk_approved_by',
            'liquidation',
            'fk_approved_by',
            'employee',
            'employee_id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-liquidation-fk_certified_by', 'liquidation',);
        $this->dropIndex('idx-liquidation-fk_certified_by', 'liquidation');
        $this->dropColumn('liquidation', 'fk_certified_by', $this->bigInteger());

        $this->dropForeignKey('fk-liquidation-fk_approved_by', 'liquidation',);
        $this->dropIndex('idx-liquidation-fk_approved_by', 'liquidation');
        $this->dropColumn('liquidation', 'fk_approved_by', $this->bigInteger());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240104_061824_add_signatories_in_liquidation_table cannot be reverted.\n";

        return false;
    }
    */
}
