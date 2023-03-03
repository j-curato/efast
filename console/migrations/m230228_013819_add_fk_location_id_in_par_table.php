<?php

use yii\db\Migration;

/**
 * Class m230228_013819_add_fk_location_id_in_par_table
 */
class m230228_013819_add_fk_location_id_in_par_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->query();
        $this->addColumn('par', 'fk_location_id', $this->integer()->notNull());
        $this->createIndex('idx-fk_location_id', 'par', 'fk_location_id');
        $this->addForeignKey('fk-par-fk_location_id', 'par', 'fk_location_id', 'location', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-par-fk_location_id', 'par');
        $this->dropIndex('idx-fk_location_id', 'par');
        $this->dropColumn('par', 'fk_location_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230228_013819_add_fk_location_id_in_par_table cannot be reverted.\n";

        return false;
    }
    */
}
