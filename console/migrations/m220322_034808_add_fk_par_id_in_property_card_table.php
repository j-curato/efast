<?php

use yii\db\Migration;

/**
 * Class m220322_034808_add_fk_par_id_in_property_card_table
 */
class m220322_034808_add_fk_par_id_in_property_card_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%property_card}}', 'fk_par_id', $this->bigInteger()->after('id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%property_card}}', 'fk_par_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220322_034808_add_fk_par_id_in_property_card_table cannot be reverted.\n";

        return false;
    }
    */
}
