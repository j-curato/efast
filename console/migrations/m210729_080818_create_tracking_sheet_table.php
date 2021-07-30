<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tracking_sheet}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%payee}}`
 * - `{{%process_ors}}`
 */
class m210729_080818_create_tracking_sheet_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tracking_sheet}}', [
            'id' => $this->primaryKey(),
            'payee_id' => $this->integer(),
            'process_ors_id' => $this->integer(),
            'tracking_number'=>$this->string(),
            'particular'=>$this->text(),
            'transaction_type'=>$this->string(),
            'gross_amount'=>$this->decimal(10,2),
            'created_at'=>$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `payee_id`
        $this->createIndex(
            '{{%idx-tracking_sheet-payee_id}}',
            '{{%tracking_sheet}}',
            'payee_id'
        );

        // add foreign key for table `{{%payee}}`
        $this->addForeignKey(
            '{{%fk-tracking_sheet-payee_id}}',
            '{{%tracking_sheet}}',
            'payee_id',
            '{{%payee}}',
            'id',
            'CASCADE'
        );

        // creates index for column `process_ors_id`
        $this->createIndex(
            '{{%idx-tracking_sheet-process_ors_id}}',
            '{{%tracking_sheet}}',
            'process_ors_id'
        );

        // add foreign key for table `{{%process_ors}}`
        $this->addForeignKey(
            '{{%fk-tracking_sheet-process_ors_id}}',
            '{{%tracking_sheet}}',
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
        // drops foreign key for table `{{%payee}}`
        $this->dropForeignKey(
            '{{%fk-tracking_sheet-payee_id}}',
            '{{%tracking_sheet}}'
        );

        // drops index for column `payee_id`
        $this->dropIndex(
            '{{%idx-tracking_sheet-payee_id}}',
            '{{%tracking_sheet}}'
        );

        // drops foreign key for table `{{%process_ors}}`
        $this->dropForeignKey(
            '{{%fk-tracking_sheet-process_ors_id}}',
            '{{%tracking_sheet}}'
        );

        // drops index for column `process_ors_id`
        $this->dropIndex(
            '{{%idx-tracking_sheet-process_ors_id}}',
            '{{%tracking_sheet}}'
        );

        $this->dropTable('{{%tracking_sheet}}');
    }
}
