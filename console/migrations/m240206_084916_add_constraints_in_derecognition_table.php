<?php

use yii\db\Migration;

/**
 * Class m240206_084916_add_constraints_in_derecognition_table
 */
class m240206_084916_add_constraints_in_derecognition_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS =0")->query();
        $this->addForeignKey("fk-derecognition-fk_property_id", 'derecognition', 'fk_property_id', 'property', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex("idx-derecognition-fk_property_id", 'derecognition');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240206_084916_add_constraints_in_derecognition_table cannot be reverted.\n";

        return false;
    }
    */
}
