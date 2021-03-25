<?php

use yii\db\Migration;

/**
 * Class m210325_032252_add_isParent_in_raouds_table
 */
class m210325_032252_add_isParent_in_raouds_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('raouds','is_parent',$this->boolean()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('raouds','is_parent');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210325_032252_add_isParent_in_raouds_table cannot be reverted.\n";

        return false;
    }
    */
}
