<?php

use yii\db\Migration;

/**
 * Class m230726_052751_add_back_date_in_pr_purchase_request_tabe
 */
class m230726_052751_add_back_date_in_pr_purchase_request_tabe extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230726_052751_add_back_date_in_pr_purchase_request_tabe cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230726_052751_add_back_date_in_pr_purchase_request_tabe cannot be reverted.\n";

        return false;
    }
    */
}
