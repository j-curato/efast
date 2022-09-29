<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%iar_transmittal_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%iar_transmittal}}`
 */
class m220929_011244_create_iar_transmittal_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%iar_transmittal_items}}', [
            'id' => $this->primaryKey(),
            'fk_iar_transmittal_id' => $this->bigInteger()->notNull(),
            'fk_iar_id' => $this->bigInteger()->notNull(),
            'is_deleted' => $this->boolean()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `fk_iar_transmittal_id`
        $this->createIndex(
            '{{%idx-iar_transmittal_items-fk_iar_transmittal_id}}',
            '{{%iar_transmittal_items}}',
            'fk_iar_transmittal_id'
        );

        // add foreign key for table `{{%iar_transmittal}}`
        $this->addForeignKey(
            '{{%fk-iar_transmittal_items-fk_iar_transmittal_id}}',
            '{{%iar_transmittal_items}}',
            'fk_iar_transmittal_id',
            '{{%iar_transmittal}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%iar_transmittal}}`
        $this->dropForeignKey(
            '{{%fk-iar_transmittal_items-fk_iar_transmittal_id}}',
            '{{%iar_transmittal_items}}'
        );

        // drops index for column `fk_iar_transmittal_id`
        $this->dropIndex(
            '{{%idx-iar_transmittal_items-fk_iar_transmittal_id}}',
            '{{%iar_transmittal_items}}'
        );

        $this->dropTable('{{%iar_transmittal_items}}');
    }
}
