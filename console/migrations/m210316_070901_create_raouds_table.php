<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%raouds}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%record_allotments}}`
 * - `{{%process_ors}}`
 */
class m210316_070901_create_raouds_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%raouds}}', [
            'id' => $this->primaryKey(),
            'record_allotment_id' => $this->integer(),
            'process_ors_id' => $this->integer(),
            'serial_number'=>$this->string(50),
            'reporting_period'=>$this->string(30)
        ]);

        // creates index for column `record_allotment_id`
        $this->createIndex(
            '{{%idx-raouds-record_allotment_id}}',
            '{{%raouds}}',
            'record_allotment_id'
        );

        // add foreign key for table `{{%record_allotments}}`
        $this->addForeignKey(
            '{{%fk-raouds-record_allotment_id}}',
            '{{%raouds}}',
            'record_allotment_id',
            '{{%record_allotments}}',
            'id',
            'CASCADE'
        );

        // creates index for column `process_ors_id`
        $this->createIndex(
            '{{%idx-raouds-process_ors_id}}',
            '{{%raouds}}',
            'process_ors_id'
        );

        // add foreign key for table `{{%process_ors}}`
        $this->addForeignKey(
            '{{%fk-raouds-process_ors_id}}',
            '{{%raouds}}',
            'process_ors_id',
            '{{%process_ors}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%record_allotments}}`
        $this->dropForeignKey(
            '{{%fk-raouds-record_allotment_id}}',
            '{{%raouds}}'
        );

        // drops index for column `record_allotment_id`
        $this->dropIndex(
            '{{%idx-raouds-record_allotment_id}}',
            '{{%raouds}}'
        );

        // drops foreign key for table `{{%process_ors}}`
        $this->dropForeignKey(
            '{{%fk-raouds-process_ors_id}}',
            '{{%raouds}}'
        );

        // drops index for column `process_ors_id`
        $this->dropIndex(
            '{{%idx-raouds-process_ors_id}}',
            '{{%raouds}}'
        );

        $this->dropTable('{{%raouds}}');
    }
}
