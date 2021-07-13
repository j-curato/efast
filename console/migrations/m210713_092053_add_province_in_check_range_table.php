<?php

use yii\db\Migration;

/**
 * Class m210713_092053_add_province_in_check_range_table
 */
class m210713_092053_add_province_in_check_range_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('check_range', 'province', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('check_range', 'province');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210713_092053_add_province_in_check_range_table cannot be reverted.\n";

        return false;
    }
    */
}
