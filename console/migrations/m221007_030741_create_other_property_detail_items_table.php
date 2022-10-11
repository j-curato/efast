<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%other_property_detail_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%other_property_details}}`
 */
class m221007_030741_create_other_property_detail_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%other_property_detail_items}}', [
            'id' => $this->primaryKey(),
            'fk_other_property_details_id' => $this->bigInteger(),
            'book_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `fk_other_property_details_id`
        $this->createIndex(
            '{{%idx-other_property_detail_items-fk_other_property_details_id}}',
            '{{%other_property_detail_items}}',
            'fk_other_property_details_id'
        );

        // add foreign key for table `{{%other_property_details}}`
        $this->addForeignKey(
            '{{%fk-other_property_detail_items-fk_other_property_details_id}}',
            '{{%other_property_detail_items}}',
            'fk_other_property_details_id',
            '{{%other_property_details}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%other_property_details}}`
        $this->dropForeignKey(
            '{{%fk-other_property_detail_items-fk_other_property_details_id}}',
            '{{%other_property_detail_items}}'
        );

        // drops index for column `fk_other_property_details_id`
        $this->dropIndex(
            '{{%idx-other_property_detail_items-fk_other_property_details_id}}',
            '{{%other_property_detail_items}}'
        );

        $this->dropTable('{{%other_property_detail_items}}');
    }
}
