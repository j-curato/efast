<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cash_recieved}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%document_recieve}}`
 * - `{{%books}}`
 * - `{{%mfo_pap_code}}`
 */
class m210412_013034_create_cash_recieved_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cash_recieved}}', [
            'id' => $this->primaryKey(),
            'document_recieved_id' => $this->integer(),
            'book_id' => $this->integer(),
            'mfo_pap_code_id' => $this->integer(),
            'date'=>$this->string(50),
            'reporting_period'=>$this->string(40),
            'nca_no'=>$this->string(100),
            'nta_no'=>$this->string(100),
            'nft_no'=>$this->string(100),
            'purpose'=>$this->string(),
            'amount'=>$this->decimal(10,2)


        ]);

        // creates index for column `document_recieved_id`
        $this->createIndex(
            '{{%idx-cash_recieved-document_recieved_id}}',
            '{{%cash_recieved}}',
            'document_recieved_id'
        );

        // add foreign key for table `{{%document_recieve}}`
        $this->addForeignKey(
            '{{%fk-cash_recieved-document_recieved_id}}',
            '{{%cash_recieved}}',
            'document_recieved_id',
            '{{%document_recieve}}',
            'id',
            'CASCADE'
        );

        // creates index for column `book_id`
        $this->createIndex(
            '{{%idx-cash_recieved-book_id}}',
            '{{%cash_recieved}}',
            'book_id'
        );

        // add foreign key for table `{{%books}}`
        $this->addForeignKey(
            '{{%fk-cash_recieved-book_id}}',
            '{{%cash_recieved}}',
            'book_id',
            '{{%books}}',
            'id',
            'CASCADE'
        );

        // creates index for column `mfo_pap_code_id`
        $this->createIndex(
            '{{%idx-cash_recieved-mfo_pap_code_id}}',
            '{{%cash_recieved}}',
            'mfo_pap_code_id'
        );

        // add foreign key for table `{{%mfo_pap_code}}`
        $this->addForeignKey(
            '{{%fk-cash_recieved-mfo_pap_code_id}}',
            '{{%cash_recieved}}',
            'mfo_pap_code_id',
            '{{%mfo_pap_code}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%document_recieve}}`
        $this->dropForeignKey(
            '{{%fk-cash_recieved-document_recieved_id}}',
            '{{%cash_recieved}}'
        );

        // drops index for column `document_recieved_id`
        $this->dropIndex(
            '{{%idx-cash_recieved-document_recieved_id}}',
            '{{%cash_recieved}}'
        );

        // drops foreign key for table `{{%books}}`
        $this->dropForeignKey(
            '{{%fk-cash_recieved-book_id}}',
            '{{%cash_recieved}}'
        );

        // drops index for column `book_id`
        $this->dropIndex(
            '{{%idx-cash_recieved-book_id}}',
            '{{%cash_recieved}}'
        );

        // drops foreign key for table `{{%mfo_pap_code}}`
        $this->dropForeignKey(
            '{{%fk-cash_recieved-mfo_pap_code_id}}',
            '{{%cash_recieved}}'
        );

        // drops index for column `mfo_pap_code_id`
        $this->dropIndex(
            '{{%idx-cash_recieved-mfo_pap_code_id}}',
            '{{%cash_recieved}}'
        );

        $this->dropTable('{{%cash_recieved}}');
    }
}
