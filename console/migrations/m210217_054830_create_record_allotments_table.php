<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%record_allotments}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%document_recieve}}`
 * - `{{%fund_cluster_code}}`
 * - `{{%financing_source_code}}`
 * - `{{%authorization_code}}`
 * - `{{%fund_category_and_classification_code}}`
 * - `{{%mfo_pap_code}}`
 * - `{{%fund_source}}`
 */
class m210217_054830_create_record_allotments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%record_allotments}}', [
            'id' => $this->primaryKey(),
            'document_recieved_id' => $this->integer()->notNull(),
            'fund_cluster_code_id' => $this->integer()->notNull(),
            'financing_source_code_id' => $this->integer()->notNull(),
            'fund_category_and_classification_code_id' => $this->integer()->notNull(),
            'authorization_code_id' => $this->integer()->notNull(),
            'mfo_pap_code_id' => $this->integer()->notNull(),
            'fund_source_id' => $this->integer()->notNull(),
            'reporting_period'=>$this->string(20),
            'serial_number'=>$this->string(255),
            'allotment_number'=>$this->string(255),
            'date_issued'=>$this->string(50),
            'valid_until'=>$this->string(50),
            'particulars'=>$this->text(),

        ]);

        // creates index for column `document_recieved_id`
        $this->createIndex(
            '{{%idx-record_allotments-document_recieved_id}}',
            '{{%record_allotments}}',
            'document_recieved_id'
        );

        // add foreign key for table `{{%document_recieve}}`
        $this->addForeignKey(
            '{{%fk-record_allotments-document_recieved_id}}',
            '{{%record_allotments}}',
            'document_recieved_id',
            '{{%document_recieve}}',
            'id',
            'CASCADE'
        );

        // creates index for column `fund_cluster_code_id`
        $this->createIndex(
            '{{%idx-record_allotments-fund_cluster_code_id}}',
            '{{%record_allotments}}',
            'fund_cluster_code_id'
        );

        // add foreign key for table `{{%fund_cluster_code}}`
        $this->addForeignKey(
            '{{%fk-record_allotments-fund_cluster_code_id}}',
            '{{%record_allotments}}',
            'fund_cluster_code_id',
            '{{%fund_cluster_code}}',
            'id',
            'CASCADE'
        );

        // creates index for column `financing_source_code_id`
        $this->createIndex(
            '{{%idx-record_allotments-financing_source_code_id}}',
            '{{%record_allotments}}',
            'financing_source_code_id'
        );

        // add foreign key for table `{{%financing_source_code}}`
        $this->addForeignKey(
            '{{%fk-record_allotments-financing_source_code_id}}',
            '{{%record_allotments}}',
            'financing_source_code_id',
            '{{%financing_source_code}}',
            'id',
            'CASCADE'
        );

        // creates index for column `authorization_code_id`
        $this->createIndex(
            '{{%idx-record_allotments-authorization_code_id}}',
            '{{%record_allotments}}',
            'authorization_code_id'
        );

        // add foreign key for table `{{%authorization_code}}`
        $this->addForeignKey(
            '{{%fk-record_allotments-authorization_code_id}}',
            '{{%record_allotments}}',
            'authorization_code_id',
            '{{%authorization_code}}',
            'id',
            'CASCADE'
        );

        // creates index for column `fund_category_id`
        $this->createIndex(
            '{{%idx-record_allotments-fund_category_id}}',
            '{{%record_allotments}}',
            'fund_category_id'
        );

        // add foreign key for table `{{%fund_category_and_classification_code}}`
        $this->addForeignKey(
            '{{%fk-record_allotments-fund_category_id}}',
            '{{%record_allotments}}',
            'fund_category_id',
            '{{%fund_category_and_classification_code}}',
            'id',
            'CASCADE'
        );

        // creates index for column `mfo_pap_code_id`
        $this->createIndex(
            '{{%idx-record_allotments-mfo_pap_code_id}}',
            '{{%record_allotments}}',
            'mfo_pap_code_id'
        );

        // add foreign key for table `{{%mfo_pap_code}}`
        $this->addForeignKey(
            '{{%fk-record_allotments-mfo_pap_code_id}}',
            '{{%record_allotments}}',
            'mfo_pap_code_id',
            '{{%mfo_pap_code}}',
            'id',
            'CASCADE'
        );

        // creates index for column `fund_source_id`
        $this->createIndex(
            '{{%idx-record_allotments-fund_source_id}}',
            '{{%record_allotments}}',
            'fund_source_id'
        );

        // add foreign key for table `{{%fund_source}}`
        $this->addForeignKey(
            '{{%fk-record_allotments-fund_source_id}}',
            '{{%record_allotments}}',
            'fund_source_id',
            '{{%fund_source}}',
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
            '{{%fk-record_allotments-document_recieved_id}}',
            '{{%record_allotments}}'
        );

        // drops index for column `document_recieved_id`
        $this->dropIndex(
            '{{%idx-record_allotments-document_recieved_id}}',
            '{{%record_allotments}}'
        );

        // drops foreign key for table `{{%fund_cluster_code}}`
        $this->dropForeignKey(
            '{{%fk-record_allotments-fund_cluster_code_id}}',
            '{{%record_allotments}}'
        );

        // drops index for column `fund_cluster_code_id`
        $this->dropIndex(
            '{{%idx-record_allotments-fund_cluster_code_id}}',
            '{{%record_allotments}}'
        );

        // drops foreign key for table `{{%financing_source_code}}`
        $this->dropForeignKey(
            '{{%fk-record_allotments-financing_source_code_id}}',
            '{{%record_allotments}}'
        );

        // drops index for column `financing_source_code_id`
        $this->dropIndex(
            '{{%idx-record_allotments-financing_source_code_id}}',
            '{{%record_allotments}}'
        );

        // drops foreign key for table `{{%authorization_code}}`
        $this->dropForeignKey(
            '{{%fk-record_allotments-authorization_code_id}}',
            '{{%record_allotments}}'
        );

        // drops index for column `authorization_code_id`
        $this->dropIndex(
            '{{%idx-record_allotments-authorization_code_id}}',
            '{{%record_allotments}}'
        );

        // drops foreign key for table `{{%fund_category_and_classification_code}}`
        $this->dropForeignKey(
            '{{%fk-record_allotments-fund_category_id}}',
            '{{%record_allotments}}'
        );

        // drops index for column `fund_category_id`
        $this->dropIndex(
            '{{%idx-record_allotments-fund_category_id}}',
            '{{%record_allotments}}'
        );

        // drops foreign key for table `{{%mfo_pap_code}}`
        $this->dropForeignKey(
            '{{%fk-record_allotments-mfo_pap_code_id}}',
            '{{%record_allotments}}'
        );

        // drops index for column `mfo_pap_code_id`
        $this->dropIndex(
            '{{%idx-record_allotments-mfo_pap_code_id}}',
            '{{%record_allotments}}'
        );

        // drops foreign key for table `{{%fund_source}}`
        $this->dropForeignKey(
            '{{%fk-record_allotments-fund_source_id}}',
            '{{%record_allotments}}'
        );

        // drops index for column `fund_source_id`
        $this->dropIndex(
            '{{%idx-record_allotments-fund_source_id}}',
            '{{%record_allotments}}'
        );

        $this->dropTable('{{%record_allotments}}');
    }
}
