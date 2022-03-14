<?php

use yii\db\Migration;

/**
 * Class m220309_005711_add_id_in_property_card_table
 */
class m220309_005711_add_id_in_property_card_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('property_card', 'id', $this->bigInteger()->notNull());
        $this->addColumn('property_card', 'created_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
        $this->dropPrimaryKey('PRIMARY', 'property_card');
        $this->addPrimaryKey('pd-id', 'property_card', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey('PRIMARY', 'property_card');
        $this->dropColumn('property_card', 'id',);
        $this->addPrimaryKey('pd-id', 'property_card', 'pc_number');
        $this->dropColumn('property_card', 'created_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220309_005711_add_id_in_property_card_table cannot be reverted.\n";

        return false;
    }
    */
}
