<?php

use yii\db\Migration;

/**
 * Class m210323_081732_add_fund_classification_to_record_allotments_table
 */
class m210323_081732_add_fund_classification_to_record_allotments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('record_allotments', 'fund_classification', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('record_allotments', 'fund_classification');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210323_081732_add_fund_classification_to_record_allotments_table cannot be reverted.\n";

        return false;
    }
    */
}
