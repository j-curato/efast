<?php

use yii\db\Migration;

/**
 * Class m221012_015409_add_fk_ssf_category_id_in_property_table
 */
class m221012_015409_add_fk_ssf_category_id_in_property_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('property', 'fk_ssf_category_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('property', 'fk_ssf_category_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221012_015409_add_fk_ssf_category_id_in_property_table cannot be reverted.\n";

        return false;
    }
    */
}
