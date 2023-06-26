<?php

use yii\db\Migration;

/**
 * Class m230626_020033_change_fk_cash_disbursement_item_id_to_fk_cash_disbursement_id_in_rci_items_table
 */
class m230626_020033_change_fk_cash_disbursement_item_id_to_fk_cash_disbursement_id_in_rci_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // drops foreign key for table `{{%cash_disbursement_items}}`
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 0")->query();
        $this->dropForeignKey(
            '{{%fk-rci_items-fk_cash_disbursement_item_id}}',
            '{{%rci_items}}'
        );

        // drops index for column `fk_cash_disbursement_item_id`
        $this->dropIndex(
            '{{%idx-rci_items-fk_cash_disbursement_item_id}}',
            '{{%rci_items}}'
        );
        $this->renameColumn('rci_items', 'fk_cash_disbursement_item_id', 'fk_cash_disbursement_id');
        $this->alterColumn('rci_items', 'fk_cash_disbursement_id', $this->bigInteger());
        $this->createIndex(
            '{{%idx-rci_items-fk_cash_disbursement_id}}',
            '{{%rci_items}}',
            'fk_cash_disbursement_id'
        );
        $this->addForeignKey(
            '{{%fk-rci_items-fk_cash_disbursement_id}}',
            '{{%rci_items}}',
            'fk_cash_disbursement_id',
            'cash_disbursement',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 0")->query();
        $this->dropForeignKey(
            '{{%fk-rci_items-fk_cash_disbursement_id}}',
            '{{%rci_items}}',
            'fk_cash_disbursement_id',

        );
        $this->dropIndex(
            '{{%idx-rci_items-fk_cash_disbursement_id}}',
            '{{%rci_items}}'
        );

        $this->renameColumn('rci_items', 'fk_cash_disbursement_id', 'fk_cash_disbursement_item_id');
        $this->alterColumn('rci_items', 'fk_cash_disbursement_item_id', $this->integer());
        // // drops foreign key for table `{{%cash_disbursement_items}}`


        // drops index for column `fk_cash_disbursement_item_id`
        $this->createIndex(
            '{{%idx-rci_items-fk_cash_disbursement_item_id}}',
            '{{%rci_items}}',
            'fk_cash_disbursement_item_id'
        );
        $this->addForeignKey(
            '{{%fk-rci_items-fk_cash_disbursement_item_id}}',
            '{{%rci_items}}',
            'fk_cash_disbursement_item_id',
            'cash_disbursement_items',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230626_020033_change_fk_cash_disbursement_item_id_to_fk_cash_disbursement_id_in_rci_items_table cannot be reverted.\n";

        return false;
    }
    */
}
