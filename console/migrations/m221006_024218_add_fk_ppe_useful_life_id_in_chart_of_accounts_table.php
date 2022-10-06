<?php

use yii\db\Migration;

/**
 * Class m221006_024218_add_fk_ppe_useful_life_id_in_chart_of_accounts_table
 */
class m221006_024218_add_fk_ppe_useful_life_id_in_chart_of_accounts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('chart_of_accounts', 'fk_ppe_useful_life_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('chart_of_accounts', 'fk_ppe_useful_life_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221006_024218_add_fk_ppe_useful_life_id_in_chart_of_accounts_table cannot be reverted.\n";

        return false;
    }
    */
}
