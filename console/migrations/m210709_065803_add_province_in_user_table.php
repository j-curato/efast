<?php

use yii\db\Migration;

/**
 * Class m210709_065803_add_province_in_user_table
 */
class m210709_065803_add_province_in_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user','province',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user','province');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210709_065803_add_province_in_user_table cannot be reverted.\n";

        return false;
    }
    */
}
