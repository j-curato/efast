<?php

use yii\db\Migration;

/**
 * Class m231013_004912_add_to_date_in_notice_of_postponement_table
 */
class m231013_004912_add_to_date_in_notice_of_postponement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('notice_of_postponement', 'to_date', $this->timestamp()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('notice_of_postponement', 'to_date');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231013_004912_add_to_date_in_notice_of_postponement_table cannot be reverted.\n";

        return false;
    }
    */
}
