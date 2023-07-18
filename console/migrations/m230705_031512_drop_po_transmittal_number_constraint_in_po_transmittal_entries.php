<?php

use yii\db\Migration;

/**
 * Class m230705_031512_drop_po_transmittal_number_constraint_in_po_transmittal_entries
 */
class m230705_031512_drop_po_transmittal_number_constraint_in_po_transmittal_entries extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // $this->dropForeignKey('fk-po_transmittal_entries-po_transmittal_number', 'po_transmittal_entries');
        // $this->dropIndex('idx-po_transmittal_entries-po_transmittal_number', 'po_transmittal_entries');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // // // creates index for column `po_transmittal_number`
        // $this->createIndex(
        //     '{{%idx-po_transmittal_entries-po_transmittal_number}}',
        //     '{{%po_transmittal_entries}}',
        //     'po_transmittal_number'
        // );

        // // add foreign key for table `{{%po_transmittal}}`
        // $this->addForeignKey(
        //     '{{%fk-po_transmittal_entries-po_transmittal_number}}',
        //     '{{%po_transmittal_entries}}',
        //     'po_transmittal_number',
        //     '{{%po_transmittal}}',
        //     'transmittal_number',
        //     'CASCADE'
        // );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230705_031512_drop_po_transmittal_number_constraint_in_po_transmittal_entries cannot be reverted.\n";

        return false;
    }
    */
}
