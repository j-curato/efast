<?php

use yii\db\Migration;

/**
 * Class m210708_090204_remove_advance_type_in_other_reciepts_tabe
 */
class m210708_090204_remove_advance_type_in_other_reciepts_tabe extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('other_reciepts', 'advance_type');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('other_reciepts', 'advance_type', $this->string());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210708_090204_remove_advance_type_in_other_reciepts_tabe cannot be reverted.\n";

        return false;
    }
    */
}
