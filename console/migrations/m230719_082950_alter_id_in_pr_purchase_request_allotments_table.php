<?php

use yii\db\Migration;

/**
 * Class m230719_082950_alter_id_in_pr_purchase_request_allotments_table
 */
class m230719_082950_alter_id_in_pr_purchase_request_allotments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {




        $this->alterColumn('transaction_pr_items', 'fk_pr_allotment_id', $this->bigInteger());
        $this->alterColumn('pr_purchase_request_allotments', 'id', $this->bigInteger());


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
        echo "m230719_082950_alter_id_in_pr_purchase_request_allotments_table cannot be reverted.\n";

        return false;
    }
    */
}
