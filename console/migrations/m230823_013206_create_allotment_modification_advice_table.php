<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%allotment_modification_advice}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%office}}`
 * - `{{%divisions}}`
 * - `{{%book}}`
 * - `{{%allotment_type}}`
 * - `{{%mfo_pap_code}}`
 * - `{{%document_recieve}}`
 * - `{{%fund_source}}`
 */
class m230823_013206_create_allotment_modification_advice_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%allotment_modification_advice}}', [
            'id' => $this->primaryKey(),
            'serial_num' => $this->string()->notNull()->unique(),
            'date' => $this->date()->notNull(),
            'particulars' => $this->text(),
            'reporting_period' => $this->string()->notNull(),
            'fk_book_id' => $this->integer()->notNull(),
            'fk_allotment_type_id' => $this->integer()->notNull(),
            'fk_mfo_pap_id' => $this->integer()->notNull(),
            'fk_document_receive_id' => $this->integer()->notNull(),
            'fk_fund_source' => $this->integer()->notNull(),

            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('allotment_modification_advice', 'id', $this->bigInteger());

        // creates index for column `fk_book_id`
        $this->createIndex(
            '{{%idx-allotment_modification_advice-fk_book_id}}',
            '{{%allotment_modification_advice}}',
            'fk_book_id'
        );

        // add foreign key for table `{{%book}}`
        $this->addForeignKey(
            '{{%fk-allotment_modification_advice-fk_book_id}}',
            '{{%allotment_modification_advice}}',
            'fk_book_id',
            '{{%books}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `fk_allotment_type_id`
        $this->createIndex(
            '{{%idx-allotment_modification_advice-fk_allotment_type_id}}',
            '{{%allotment_modification_advice}}',
            'fk_allotment_type_id'
        );

        // add foreign key for table `{{%allotment_type}}`
        $this->addForeignKey(
            '{{%fk-allotment_modification_advice-fk_allotment_type_id}}',
            '{{%allotment_modification_advice}}',
            'fk_allotment_type_id',
            '{{%allotment_type}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `fk_mfo_pap_id`
        $this->createIndex(
            '{{%idx-allotment_modification_advice-fk_mfo_pap_id}}',
            '{{%allotment_modification_advice}}',
            'fk_mfo_pap_id'
        );

        // add foreign key for table `{{%mfo_pap_code}}`
        $this->addForeignKey(
            '{{%fk-allotment_modification_advice-fk_mfo_pap_id}}',
            '{{%allotment_modification_advice}}',
            'fk_mfo_pap_id',
            '{{%mfo_pap_code}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `fk_document_receive_id`
        $this->createIndex(
            '{{%idx-allotment_modification_advice-fk_document_receive_id}}',
            '{{%allotment_modification_advice}}',
            'fk_document_receive_id'
        );

        // add foreign key for table `{{%document_recieve}}`
        $this->addForeignKey(
            '{{%fk-allotment_modification_advice-fk_document_receive_id}}',
            '{{%allotment_modification_advice}}',
            'fk_document_receive_id',
            '{{%document_recieve}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `fk_fund_source`
        $this->createIndex(
            '{{%idx-allotment_modification_advice-fk_fund_source}}',
            '{{%allotment_modification_advice}}',
            'fk_fund_source'
        );

        // add foreign key for table `{{%fund_source}}`
        $this->addForeignKey(
            '{{%fk-allotment_modification_advice-fk_fund_source}}',
            '{{%allotment_modification_advice}}',
            'fk_fund_source',
            '{{%fund_source}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {


        // drops foreign key for table `{{%book}}`
        $this->dropForeignKey(
            '{{%fk-allotment_modification_advice-fk_book_id}}',
            '{{%allotment_modification_advice}}'
        );

        // drops index for column `fk_book_id`
        $this->dropIndex(
            '{{%idx-allotment_modification_advice-fk_book_id}}',
            '{{%allotment_modification_advice}}'
        );

        // drops foreign key for table `{{%allotment_type}}`
        $this->dropForeignKey(
            '{{%fk-allotment_modification_advice-fk_allotment_type_id}}',
            '{{%allotment_modification_advice}}'
        );

        // drops index for column `fk_allotment_type_id`
        $this->dropIndex(
            '{{%idx-allotment_modification_advice-fk_allotment_type_id}}',
            '{{%allotment_modification_advice}}'
        );

        // drops foreign key for table `{{%mfo_pap_code}}`
        $this->dropForeignKey(
            '{{%fk-allotment_modification_advice-fk_mfo_pap_id}}',
            '{{%allotment_modification_advice}}'
        );

        // drops index for column `fk_mfo_pap_id`
        $this->dropIndex(
            '{{%idx-allotment_modification_advice-fk_mfo_pap_id}}',
            '{{%allotment_modification_advice}}'
        );

        // drops foreign key for table `{{%document_recieve}}`
        $this->dropForeignKey(
            '{{%fk-allotment_modification_advice-fk_document_receive_id}}',
            '{{%allotment_modification_advice}}'
        );

        // drops index for column `fk_document_receive_id`
        $this->dropIndex(
            '{{%idx-allotment_modification_advice-fk_document_receive_id}}',
            '{{%allotment_modification_advice}}'
        );

        // drops foreign key for table `{{%fund_source}}`
        $this->dropForeignKey(
            '{{%fk-allotment_modification_advice-fk_fund_source}}',
            '{{%allotment_modification_advice}}'
        );

        // drops index for column `fk_fund_source`
        $this->dropIndex(
            '{{%idx-allotment_modification_advice-fk_fund_source}}',
            '{{%allotment_modification_advice}}'
        );

        $this->dropTable('{{%allotment_modification_advice}}');
    }
}
