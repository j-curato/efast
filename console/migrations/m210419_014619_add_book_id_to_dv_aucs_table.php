<?php

use yii\db\Migration;

/**
 * Class m210419_014619_add_book_id_to_dv_aucs_table
 */
class m210419_014619_add_book_id_to_dv_aucs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
$this->addColumn('dv_aucs','book_id',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('dv_aucs','book_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210419_014619_add_book_id_to_dv_aucs_table cannot be reverted.\n";

        return false;
    }
    */
}
