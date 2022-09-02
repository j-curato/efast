<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ppmp_non_cse_item_categories}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%ppmp_non_cse_items}}`
 */
class m220901_023300_create_ppmp_non_cse_item_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ppmp_non_cse_item_categories}}', [
            'id' => $this->primaryKey(),
            'ppmp_non_cse_item_id' => $this->bigInteger(),
            'fk_stock_type' => $this->bigInteger()->notNull(),
            'budget' => $this->decimal(10, 2),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `ppmp_non_cse_item_id`
        $this->createIndex(
            '{{%idx-ppmp_non_cse_item_categories-ppmp_non_cse_item_id}}',
            '{{%ppmp_non_cse_item_categories}}',
            'ppmp_non_cse_item_id'
        );

        // add foreign key for table `{{%ppmp_non_cse_items}}`
        $this->addForeignKey(
            '{{%fk-ppmp_non_cse_item_categories-ppmp_non_cse_item_id}}',
            '{{%ppmp_non_cse_item_categories}}',
            'ppmp_non_cse_item_id',
            '{{%ppmp_non_cse_items}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%ppmp_non_cse_items}}`
        $this->dropForeignKey(
            '{{%fk-ppmp_non_cse_item_categories-ppmp_non_cse_item_id}}',
            '{{%ppmp_non_cse_item_categories}}'
        );

        // drops index for column `ppmp_non_cse_item_id`
        $this->dropIndex(
            '{{%idx-ppmp_non_cse_item_categories-ppmp_non_cse_item_id}}',
            '{{%ppmp_non_cse_item_categories}}'
        );

        $this->dropTable('{{%ppmp_non_cse_item_categories}}');
    }
}
