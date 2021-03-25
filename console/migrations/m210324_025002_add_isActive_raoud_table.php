<?php

use yii\db\Migration;

/**
 * Class m210324_025002_add_isActive_raoud_table
 */
class m210324_025002_add_isActive_raoud_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('raouds','isActive',$this->boolean()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('raouds','isActive');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210324_025002_add_isActive_raoud_table cannot be reverted.\n";

        return false;
    }
    */
}
