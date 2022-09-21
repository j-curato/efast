<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%purchase_order_transmittal_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%purchase_order_transmittal}}`
 */
class m220914_062225_create_purchase_order_transmittal_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%purchase_order_transmittal_items}}', [
            'id' => $this->primaryKey(),
            'fk_purchase_order_transmittal_id' => $this->bigInteger()->notNull(),
            'fk_purchase_order_item_id' => $this->bigInteger()->notNull()
        ]);

        // creates index for column `fk_purchase_order_transmittal_id`
        $this->createIndex(
            '{{%idx-fk_purchase_order_transmittal_id}}',
            '{{%purchase_order_transmittal_items}}',
            'fk_purchase_order_transmittal_id'
        );

        // add foreign key for table `{{%purchase_order_transmittal}}`
        $this->addForeignKey(
            '{{%fk_purchase_order_transmittal_id}}',
            '{{%purchase_order_transmittal_items}}',
            'fk_purchase_order_transmittal_id',
            '{{%purchase_order_transmittal}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%purchase_order_transmittal}}`
        $this->dropForeignKey(
            '{{%fk_purchase_order_transmittal_id}}',
            '{{%purchase_order_transmittal_items}}'
        );

        // drops index for column `fk_purchase_order_transmittal_id`
        $this->dropIndex(
            '{{%idx-fk_purchase_order_transmittal_id}}',
            '{{%purchase_order_transmittal_items}}'
        );

        $this->dropTable('{{%purchase_order_transmittal_items}}');
    }
}
