<?php

use yii\db\Migration;

/**
 * Class m210806_032847_add_advance_type_in_advances_table
 */
class m210806_032847_add_advance_type_in_advances_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advances','advances_type',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('advances','advances_type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210806_032847_add_advance_type_in_advances_table cannot be reverted.\n";

        return false;
    }
    */
}
