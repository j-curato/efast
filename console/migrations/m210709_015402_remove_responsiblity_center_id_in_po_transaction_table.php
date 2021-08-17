<?php

use yii\db\Migration;

/**
 * Class m210709_015402_remove_responsiblity_center_id_in_po_transaction_table
 */
class m210709_015402_remove_responsiblity_center_id_in_po_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // drops foreign key for table `{{%advances}}`
        // $this->dropForeignKey(
        //     '{{%fk-po_transaction-responsibility_center_id}}',
        //     '{{%po_transaction}}'
        // );

        // // drops index for column `responsibility_center_id`
        // $this->dropIndex(
        //     '{{%idx-po_transaction-responsibility_center_id}}',
        //     '{{%po_transaction}}'
        // );
        // $this->dropColumn('po_transaction', 'responsibility_center_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // $this->addColumn('po_transaction', 'responsibility_center_id', $this->integer());
        // $this->createIndex(
        //     '{{%idx-po_transaction-responsibility_center_id}}',
        //     '{{%po_transaction}}',
        //     'responsibility_center_id'
        // );

        // // add foreign key for table `{{%transaction}}`
        // $this->addForeignKey(
        //     '{{%fk-po_transaction-responsibility_center_id}}',
        //     '{{%po_transaction}}',
        //     'responsibility_center_id',
        //     '{{%responsibility_center}}',
        //     'id',
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
        echo "m210709_015402_remove_responsiblity_center_id_in_po_transaction_table cannot be reverted.\n";

        return false;
    }
    */
}
