<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%travel_order_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%travel_order}}`
 */
class m221123_030931_create_travel_order_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%travel_order_items}}', [
            'id' => $this->primaryKey(),
            'fk_travel_order_id' => $this->bigInteger()->notNull(),
            'fk_employee_id' => $this->bigInteger()->notNull(),
            'from_date' => $this->date(),
            'to_date' => $this->date(),
            'is_deleted' => $this->boolean()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // creates index for column `fk_travel_order_id`
        $this->createIndex(
            '{{%idx-travel_order_items-fk_travel_order_id}}',
            '{{%travel_order_items}}',
            'fk_travel_order_id'
        );

        // add foreign key for table `{{%travel_order}}`
        $this->addForeignKey(
            '{{%fk-travel_order_items-fk_travel_order_id}}',
            '{{%travel_order_items}}',
            'fk_travel_order_id',
            '{{%travel_order}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%travel_order}}`
        $this->dropForeignKey(
            '{{%fk-travel_order_items-fk_travel_order_id}}',
            '{{%travel_order_items}}'
        );

        // drops index for column `fk_travel_order_id`
        $this->dropIndex(
            '{{%idx-travel_order_items-fk_travel_order_id}}',
            '{{%travel_order_items}}'
        );

        $this->dropTable('{{%travel_order_items}}');
    }
}
