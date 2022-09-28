<?php

use yii\db\Migration;

/**
 * Class m220922_005345_add_province_in_property_table
 */
class m220922_005345_add_province_in_property_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('property', 'province', $this->string()->defaultValue('ro'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('property', 'province');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220922_005345_add_province_in_property_table cannot be reverted.\n";

        return false;
    }
    */
}
