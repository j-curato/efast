<?php

use yii\db\Migration;

/**
 * Class m220906_025606_add_responsible_center_in_ppmp_non_cse_table
 */
class m220906_025606_add_responsible_center_in_ppmp_non_cse_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
$this->addColumn('ppmp_non_cse','responsible_center',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(){
        $this->dropColumn('ppmp_non_cse','responsible_center');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220906_025606_add_responsible_center_in_ppmp_non_cse_table cannot be reverted.\n";

        return false;
    }
    */
}
