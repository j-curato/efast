<?php

use yii\db\Migration;

/**
 * Class m230705_034154_add_fk_office_id_in_po_transmittal_table
 */
class m230705_034154_add_fk_office_id_in_po_transmittal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('po_transmittal', 'fk_office_id', $this->integer());
        $this->createIndex('idx-po-trnmtl-', 'po_transmittal', 'fk_office_id');
        $this->addForeignKey('fk-po-trnmtl-', 'po_transmittal', 'fk_office_id', 'office', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-po-trnmtl-', 'po_transmittal');
        $this->dropIndex('idx-po-trnmtl-', 'po_transmittal');
        $this->dropColumn('po_transmittal', 'fk_office_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230705_034154_add_fk_office_id_in_po_transmittal_table cannot be reverted.\n";

        return false;
    }
    */
}
