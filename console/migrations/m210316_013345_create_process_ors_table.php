<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%process_ors}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%transaction}}`
 */
class m210316_013345_create_process_ors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%process_ors}}', [
            'id' => $this->primaryKey(),
            'transaction_id' => $this->integer(),
            'reporting_period'=>$this->string(),
            'serial_number'=>$this->string(),
            'obligation_number'=>$this->string(),
            'funding_code'=>$this->string(50),
            'document_recieve_id'=>$this->integer(),
            'mfo_pap_code_id'=>$this->integer(),
            'fund_source_id'=>$this->integer()
        ]);

        // creates index for column `transaction_id`
        $this->createIndex(
            '{{%idx-process_ors-transaction_id}}',
            '{{%process_ors}}',
            'transaction_id'
        );

        // add foreign key for table `{{%transaction}}`
        $this->addForeignKey(
            '{{%fk-process_ors-transaction_id}}',
            '{{%process_ors}}',
            'transaction_id',
            '{{%transaction}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%transaction}}`
        $this->dropForeignKey(
            '{{%fk-process_ors-transaction_id}}',
            '{{%process_ors}}'
        );

        // drops index for column `transaction_id`
        $this->dropIndex(
            '{{%idx-process_ors-transaction_id}}',
            '{{%process_ors}}'
        );

        $this->dropTable('{{%process_ors}}');
    }
}
