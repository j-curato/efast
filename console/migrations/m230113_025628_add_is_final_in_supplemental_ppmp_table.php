<?php

use yii\db\Migration;

/**
 * Class m230113_025628_add_is_final_in_supplemental_ppmp_table
 */
class m230113_025628_add_is_final_in_supplemental_ppmp_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('supplemental_ppmp', 'is_final', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('supplemental_ppmp', 'is_final');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230113_025628_add_is_final_in_supplemental_ppmp_table cannot be reverted.\n";

        return false;
    }
    */
}
