<?php

use yii\db\Migration;

/**
 * Class m240212_012601_add_constraints_in_po_transmittal_entries_table
 */
class m240212_012601_add_constraints_in_po_transmittal_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->query();
        $this->createIndex('idx-fk_po_transmittal_id-po_transmittal_entries', 'po_transmittal_entries', 'fk_po_transmittal_id');
        $this->addForeignKey(
            'fk-fk_po_transmittal_id-po_transmittal_entries',
            'po_transmittal_entries',
            'fk_po_transmittal_id',
            'po_transmittal',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-fk_po_transmittal_id-po_transmittal_entries',
            'po_transmittal_entries'
        );
        $this->dropIndex('idx-fk_po_transmittal_id-po_transmittal_entries', 'po_transmittal_entries');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240212_012601_add_constraints_in_po_transmittal_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
