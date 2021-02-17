<?php

use yii\db\Migration;

/**
 * Class m210217_052221_add_payee_id_to_table
 */
class m210217_052221_add_payee_id_to_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('jev_preparation','payee_id',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('jev_preparation','payee_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210217_052221_add_payee_id_to_table cannot be reverted.\n";

        return false;
    }
    */
}
