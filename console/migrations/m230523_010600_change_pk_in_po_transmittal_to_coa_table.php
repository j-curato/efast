<?php

use yii\db\Migration;

/**
 * Class m230523_010600_change_pk_in_po_transmittal_to_coa_table
 */
class m230523_010600_change_pk_in_po_transmittal_to_coa_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('po_transmittal_to_coa', 'id', $this->bigInteger()->unsigned()->after('transmittal_number')->defaultValue(null)->notNull());
        $this->db->createCommand('UPDATE po_transmittal_to_coa SET id = UUID_SHORT()')->execute();
        $this->dropPrimaryKey(NULL, 'po_transmittal_to_coa');
        $this->alterColumn('po_transmittal_to_coa', 'id', $this->bigPrimaryKey());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // disable `AUTO_INCREMENT` in the table to run to migration down
        $this->dropPrimaryKey('PRIMARY', 'po_transmittal_to_coa');
        $this->alterColumn('po_transmittal_to_coa', 'transmittal_number', $this->string()->notNull());
        $this->addPrimaryKey('pk_transmittal_number', 'po_transmittal_to_coa', 'transmittal_number');
        $this->dropColumn('po_transmittal_to_coa', 'id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230523_010600_change_pk_in_po_transmittal_to_coa_table cannot be reverted.\n";

        return false;
    }
    */
}
