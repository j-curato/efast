<?php

use yii\db\Migration;

/**
 * Class m210405_014636_add_particular_to_dv_aucs_table
 */
class m210405_014636_add_particular_to_dv_aucs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dv_aucs','particular',$this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('dv_aucs','particular');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210405_014636_add_particular_to_dv_aucs_table cannot be reverted.\n";

        return false;
    }
    */
}
