<?php

use yii\db\Migration;

/**
 * Class m221012_013057_add_ppe_type_in_property_table
 */
class m221012_013057_add_ppe_type_in_property_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('property', 'ppe_type', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('property', 'ppe_type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221012_013057_add_ppe_type_in_property_table cannot be reverted.\n";

        return false;
    }
    */
}
