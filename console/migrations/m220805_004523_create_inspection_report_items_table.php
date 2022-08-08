<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%inspection_report_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%inspection_report}}`
 */
class m220805_004523_create_inspection_report_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%inspection_report_items}}', [
            'id' => $this->primaryKey(),
            'fk_inspection_report_id' => $this->bigInteger(),
            'fk_request_for_inspection_item_id' => $this->integer(),
            'is_deleted' => $this->boolean()->defaultValue(false),

        ]);
        $this->alterColumn('inspection_report_items', 'id', $this->bigInteger());

        // creates index for column `fk_inspection_report_id`
        $this->createIndex(
            '{{%idx-inspection_report_items-fk_inspection_report_id}}',
            '{{%inspection_report_items}}',
            'fk_inspection_report_id'
        );

        // add foreign key for table `{{%inspection_report}}`
        $this->addForeignKey(
            '{{%fk-inspection_report_items-fk_inspection_report_id}}',
            '{{%inspection_report_items}}',
            'fk_inspection_report_id',
            '{{%inspection_report}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%inspection_report}}`
        $this->dropForeignKey(
            '{{%fk-inspection_report_items-fk_inspection_report_id}}',
            '{{%inspection_report_items}}'
        );

        // drops index for column `fk_inspection_report_id`
        $this->dropIndex(
            '{{%idx-inspection_report_items-fk_inspection_report_id}}',
            '{{%inspection_report_items}}'
        );

        $this->dropTable('{{%inspection_report_items}}');
    }
}
