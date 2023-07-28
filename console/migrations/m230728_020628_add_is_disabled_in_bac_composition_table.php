<?php

use yii\db\Migration;

/**
 * Class m230728_020628_add_is_disabled_in_bac_composition_table
 */
class m230728_020628_add_is_disabled_in_bac_composition_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bac_composition', 'is_disabled', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bac_composition', 'is_disabled');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230728_020628_add_is_disabled_in_bac_composition_table cannot be reverted.\n";

        return false;
    }
    */
}
