<?php

use yii\db\Migration;

/**
 * Class m230228_051547_update_property_card_table
 */
class m230228_051547_update_property_card_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('property_card', 'id', $this->bigInteger()->after('pc_number'));
        $this->alterColumn('property_card', 'pc_number', $this->string()->after('id'));
        $this->renameColumn('property_card', 'pc_number', 'serial_number');
        $this->dropColumn('property_card', 'par_number');
        $this->dropColumn('property_card', 'ptr_number');
        $this->createIndex('idx-fk_par_id', 'property_card', 'fk_par_id');
        $this->addForeignKey('fk-pc-fk_par_id', 'property_card', 'fk_par_id', 'par', 'id', 'RESTRICT', 'CASCADE');
        $this->addColumn('property_card', 'created_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-pc-fk_par_id', 'property_card');
        $this->dropIndex('idx-fk_par_id', 'property_card');
        $this->renameColumn('property_card', 'serial_number', 'pc_number');
        $this->addColumn('property_card', 'par_number', $this->string());
        $this->addColumn('property_card', 'ptr_number', $this->string());

        $this->dropColumn('property_card', 'created_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230228_051547_update_property_card_table cannot be reverted.\n";

        return false;
    }
    */
}
