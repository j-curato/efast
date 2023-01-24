<?php

use yii\db\Migration;

/**
 * Class m230113_012125_fk_constraints_in_advances_table
 */
class m230113_012125_fk_constraints_in_advances_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //         bank_account_id
        // dv_aucs_id

        $this->createIndex('idx-bank_account_id', 'advances', 'bank_account_id');
        $this->createIndex('idx-dv_aucs_id', 'advances', 'dv_aucs_id');
        // // // CREATE FK
        $this->addForeignKey(
            'fk-bank_account_id',
            'advances',
            'bank_account_id',
            'bank_account',
            'id',
            'RESTRICT'
        );
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->execute();
        $this->addForeignKey(
            'fk-dv_aucs_id',
            'advances',
            'dv_aucs_id',
            'dv_aucs',
            'id',
            'RESTRICT'
        );
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=1")->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk-dv_aucs_id', 'advances');
        $this->dropForeignKey('fk-bank_account_id', 'advances');
        $this->dropIndex('idx-bank_account_id', 'advances');
        $this->dropIndex('idx-dv_aucs_id', 'advances');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230113_012125_fk_constraints_in_advances_table cannot be reverted.\n";

        return false;
    }
    */
}
