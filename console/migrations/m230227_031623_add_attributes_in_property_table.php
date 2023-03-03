<?php

use yii\db\Migration;

/**
 * Class m230227_031623_add_attributes_in_property_table
 */
class m230227_031623_add_attributes_in_property_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->query();
        $this->addColumn('property', 'fk_ssf_sp_num_id', $this->bigInteger()->notNull());
        $this->addColumn('property', 'fk_office_id', $this->integer()->notNull());
        $this->addColumn('property', 'is_ssf', $this->integer()->defaultValue(0)->notNull());
        $this->createIndex('idx-fk_ssf_sp_num_id', 'property', 'fk_ssf_sp_num_id');
        $this->createIndex('idx-fk_office_id', 'property', 'fk_office_id');
        $this->addForeignKey(
            'fk-pty-fk_office_id',
            'property',
            'fk_office_id',
            'office',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-pty-fk_ssf_sp_num_id',
            'property',
            'fk_ssf_sp_num_id',
            'ssf_sp_num',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {


        $this->dropForeignKey('fk-pty-fk_office_id', 'property');
        $this->dropForeignKey('fk-pty-fk_ssf_sp_num_id', 'property');
        $this->dropIndex('idx-fk_ssf_sp_num_id', 'property');
        $this->dropIndex('idx-fk_office_id', 'property');
        $this->dropColumn('property', 'fk_ssf_sp_num_id');
        $this->dropColumn('property', 'fk_office_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230227_031623_add_attributes_in_property_table cannot be reverted.\n";

        return false;
    }
    */
}
