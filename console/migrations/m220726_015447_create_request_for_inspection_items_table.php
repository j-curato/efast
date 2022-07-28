<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%request_for_inspection_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%request_for_inspection}}`
 */
class m220726_015447_create_request_for_inspection_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%request_for_inspection_items}}', [
            'id' => $this->primaryKey(),
            'fk_request_for_inspection_id' => $this->bigInteger(),
            'fk_purchase_order_item_id' => $this->bigInteger(),
            'is_deleted' => $this->boolean()->defaultValue(0),
            'deleted_at' => $this->timestamp(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')

        ]);

        // creates index for column `fk_request_for_inspection_id`
        $this->createIndex(
            '{{%idx-request_for_inspection_items-fk_request_for_inspection_id}}',
            '{{%request_for_inspection_items}}',
            'fk_request_for_inspection_id'
        );

        // add foreign key for table `{{%request_for_inspection}}`
        $this->addForeignKey(
            '{{%fk-request_for_inspection_items-fk_request_for_inspection_id}}',
            '{{%request_for_inspection_items}}',
            'fk_request_for_inspection_id',
            '{{%request_for_inspection}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%request_for_inspection}}`
        $this->dropForeignKey(
            '{{%fk-request_for_inspection_items-fk_request_for_inspection_id}}',
            '{{%request_for_inspection_items}}'
        );

        // drops index for column `fk_request_for_inspection_id`
        $this->dropIndex(
            '{{%idx-request_for_inspection_items-fk_request_for_inspection_id}}',
            '{{%request_for_inspection_items}}'
        );

        $this->dropTable('{{%request_for_inspection_items}}');
    }
}
