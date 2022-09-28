<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ir_transmittal_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%ir_transmittal}}`
 */
class m220928_064109_create_ir_transmittal_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ir_transmittal_items}}', [
            'id' => $this->primaryKey(),
            'fk_ir_transmittal_id' => $this->bigInteger(),
            'fk_ir_id' => $this->bigInteger()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'is_deleted' => $this->boolean()->defaultValue(0)

        ]);

        // creates index for column `fk_ir_transmittal_id`
        $this->createIndex(
            '{{%idx-ir_transmittal_items-fk_ir_transmittal_id}}',
            '{{%ir_transmittal_items}}',
            'fk_ir_transmittal_id'
        );

        // add foreign key for table `{{%ir_transmittal}}`
        $this->addForeignKey(
            '{{%fk-ir_transmittal_items-fk_ir_transmittal_id}}',
            '{{%ir_transmittal_items}}',
            'fk_ir_transmittal_id',
            '{{%ir_transmittal}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%ir_transmittal}}`
        $this->dropForeignKey(
            '{{%fk-ir_transmittal_items-fk_ir_transmittal_id}}',
            '{{%ir_transmittal_items}}'
        );

        // drops index for column `fk_ir_transmittal_id`
        $this->dropIndex(
            '{{%idx-ir_transmittal_items-fk_ir_transmittal_id}}',
            '{{%ir_transmittal_items}}'
        );

        $this->dropTable('{{%ir_transmittal_items}}');
    }
}
