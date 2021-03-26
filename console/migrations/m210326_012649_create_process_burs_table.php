<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%process_burs}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%transaction}}`
 */
class m210326_012649_create_process_burs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%process_burs}}', [
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
            '{{%idx-process_burs-transaction_id}}',
            '{{%process_burs}}',
            'transaction_id'
        );

        // add foreign key for table `{{%transaction}}`
        $this->addForeignKey(
            '{{%fk-process_burs-transaction_id}}',
            '{{%process_burs}}',
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
            '{{%fk-process_burs-transaction_id}}',
            '{{%process_burs}}'
        );

        // drops index for column `transaction_id`
        $this->dropIndex(
            '{{%idx-process_burs-transaction_id}}',
            '{{%process_burs}}'
        );

        $this->dropTable('{{%process_burs}}');
    }
}
