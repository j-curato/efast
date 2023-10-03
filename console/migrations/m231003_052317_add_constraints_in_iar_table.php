<?php

use yii\db\Migration;

/**
 * Class m231003_052317_add_constraints_in_iar_table
 */
class m231003_052317_add_constraints_in_iar_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS = 0')->query();
        $this->createIndex('idx-iar-fk_ir_id', 'iar', 'fk_ir_id');
        $this->addForeignKey('fk-iar-fk_ir_id', 'iar', 'fk_ir_id', 'inspection_report', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-iar-fk_ir_id', 'iar', 'fk_ir_id', 'inspection_report', 'id', 'RESTRICT', 'CASCADE');
        $this->dropIndex('idx-iar-fk_ir_id', 'iar', 'fk_ir_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231003_052317_add_constraints_in_iar_table cannot be reverted.\n";

        return false;
    }
    */
}
