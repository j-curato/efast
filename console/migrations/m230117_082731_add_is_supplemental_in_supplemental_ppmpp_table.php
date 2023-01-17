<?php

use yii\db\Migration;

/**
 * Class m230117_082731_add_is_supplemental_in_supplemental_ppmpp_table
 */
class m230117_082731_add_is_supplemental_in_supplemental_ppmpp_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('supplemental_ppmp', 'is_supplemental', $this->boolean()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('supplemental_ppmp', 'is_supplemental');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230117_082731_add_is_supplemental_in_supplemental_ppmpp_table cannot be reverted.\n";

        return false;
    }
    */
}
