<?php

use yii\db\Migration;

/**
 * Class m230217_015314_add_fk_dv_transaction_type_id_in_dv_aucs_table
 */
class m230217_015314_add_fk_dv_transaction_type_id_in_dv_aucs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dv_aucs', 'fk_dv_transaction_type_id', $this->integer());
        $this->createIndex('idx-dv-fk_dv_transaction_type_id', 'dv_aucs', 'fk_dv_transaction_type_id');
        $this->addForeignKey('fk-dv-fk_dv_transaction_type_id', 'dv_aucs', 'fk_dv_transaction_type_id', 'dv_transaction_type', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-dv-fk_dv_transaction_type_id', 'dv_aucs');
        $this->dropIndex('idx-dv-fk_dv_transaction_type_id', 'dv_aucs');
        $this->dropColumn('dv_aucs', 'fk_dv_transaction_type_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230217_015314_add_fk_dv_transaction_type_id_in_dv_aucs_table cannot be reverted.\n";

        return false;
    }
    */
}
