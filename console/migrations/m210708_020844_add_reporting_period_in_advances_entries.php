<?php

use yii\db\Migration;

/**
 * Class m210708_020844_add_reporting_period_in_advances_entries
 */
class m210708_020844_add_reporting_period_in_advances_entries extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advances_entries', 'reporting_period', $this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('advances_entries', 'reporting_period');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210708_020844_add_reporting_period_in_advances_entries cannot be reverted.\n";

        return false;
    }
    */
}
