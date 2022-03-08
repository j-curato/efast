<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pr_purchase_order_item}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%pr_purchase_order}}`
 */
class m220304_013111_create_pr_purchase_order_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pr_purchase_order_item}}', [
            'id' => $this->primaryKey(),
            'fk_pr_purchase_order_id' => $this->bigInteger(),
            'fk_pr_aoq_entries_id' => $this->integer(),
            'is_lowest' => $this->boolean()->defaultValue(0)

        ]);

        // creates index for column `fk_pr_purchase_order_id`
        $this->createIndex(
            '{{%idx-pr_purchase_order_item-fk_pr_purchase_order_id}}',
            '{{%pr_purchase_order_item}}',
            'fk_pr_purchase_order_id'
        );

        // add foreign key for table `{{%pr_purchase_order}}`
        $this->addForeignKey(
            '{{%fk-pr_purchase_order_item-fk_pr_purchase_order_id}}',
            '{{%pr_purchase_order_item}}',
            'fk_pr_purchase_order_id',
            '{{%pr_purchase_order}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%pr_purchase_order}}`
        $this->dropForeignKey(
            '{{%fk-pr_purchase_order_item-fk_pr_purchase_order_id}}',
            '{{%pr_purchase_order_item}}'
        );

        // drops index for column `fk_pr_purchase_order_id`
        $this->dropIndex(
            '{{%idx-pr_purchase_order_item-fk_pr_purchase_order_id}}',
            '{{%pr_purchase_order_item}}'
        );

        $this->dropTable('{{%pr_purchase_order_item}}');
    }
}
