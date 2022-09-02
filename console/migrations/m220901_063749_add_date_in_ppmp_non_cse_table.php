<?php

use yii\db\Migration;

/**
 * Class m220901_063749_add_date_in_ppmp_non_cse_table
 */
class m220901_063749_add_date_in_ppmp_non_cse_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('ppmp_non_cse', 'date', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('ppmp_non_cse', 'date');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220901_063749_add_date_in_ppmp_non_cse_table cannot be reverted.\n";

        return false;
    }
    */
}
