<?php

use yii\db\Migration;

/**
 * Class m230906_075716_alter_columns_in_record_allotments_table
 */
class m230906_075716_alter_columns_in_record_allotments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // document_recieve_id
        // fund_cluster_code_id
        // financing_source_code_id
        // fund_category_and_classification_code_id
        // authorization_code_id
        // mfo_pap_code_id
        $this->alterColumn('record_allotments', 'document_recieve_id', $this->integer());
        $this->alterColumn('record_allotments', 'fund_cluster_code_id', $this->integer());
        $this->alterColumn('record_allotments', 'financing_source_code_id', $this->integer());
        $this->alterColumn('record_allotments', 'fund_category_and_classification_code_id', $this->integer());
        $this->alterColumn('record_allotments', 'authorization_code_id', $this->integer());
        $this->alterColumn('record_allotments', 'mfo_pap_code_id', $this->integer());
        $this->alterColumn('record_allotments', 'fund_source_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230906_075716_alter_columns_in_record_allotments_table cannot be reverted.\n";

        return false;
    }
    */
}
