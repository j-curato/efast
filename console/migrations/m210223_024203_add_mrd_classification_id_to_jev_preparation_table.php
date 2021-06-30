<?php

use yii\db\Migration;

/**
 * Class m210223_024203_add_mrd_classification_id_to_jev_preparation_table
 */
class m210223_024203_add_mrd_classification_id_to_jev_preparation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('jev_preparation', 'cash_flow_id', $this->integer());
        $this->addColumn('jev_preparation', 'mrd_classification_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('jev_preparation', 'cash_flow_id');
        $this->dropColumn('jev_preparation', 'mrd_classification_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210223_024203_add_mrd_classification_id_to_jev_preparation_table cannot be reverted.\n";

        return false;
    }
    */
}
