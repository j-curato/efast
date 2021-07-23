<?php

use yii\db\Migration;

/**
 * Class m210722_054124_add_province_liquidation_table
 */
class m210722_054124_add_province_liquidation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('liquidation', 'province', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('liquidation', 'province');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210722_054124_add_province_liquidation_table cannot be reverted.\n";

        return false;
    }
    */
}
