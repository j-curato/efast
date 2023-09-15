<?php

use yii\db\Migration;

/**
 * Class m230116_090135_add_fk_constraints_in_dv_aucs_table
 */
class m230116_090135_add_fk_constraints_in_dv_aucs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        //         dv_number
        // payee_id
        // book_id
        // payroll_id
        // fk_remittance_id
        // fk_ro_alphalist_id
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->execute();
        // $this->createIndex('idx-dv_number', 'dv_aucs', 'dv_number', true);
        $this->createIndex('idx-payee_id', 'dv_aucs', 'payee_id');
        $this->createIndex('idx-book_id', 'dv_aucs', 'book_id');
        $this->createIndex('idx-payroll_id', 'dv_aucs', 'payroll_id');
        $this->createIndex('idx-fk_remittance_id', 'dv_aucs', 'fk_remittance_id');
        $this->createIndex('idx-fk_ro_alphalist_id', 'dv_aucs', 'fk_ro_alphalist_id');
        // // CREATE FK
        $this->addForeignKey(
            'fk-payee_id',
            'dv_aucs',
            'payee_id',
            'payee',
            'id',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-book_id',
            'dv_aucs',
            'book_id',
            'books',
            'id',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-payroll_id',
            'dv_aucs',
            'payroll_id',
            'payroll',
            'id',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-fk_remittance_id',
            'dv_aucs',
            'fk_remittance_id',
            'remittance',
            'id',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-fk_ro_alphalist_id',
            'dv_aucs',
            'fk_ro_alphalist_id',
            'ro_alphalist',
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


        $this->dropForeignKey(
            'fk-payee_id',
            'dv_aucs'
        );
        $this->dropForeignKey(
            'fk-book_id',
            'dv_aucs'
        );
        $this->dropForeignKey(
            'fk-payroll_id',
            'dv_aucs'
        );
        $this->dropForeignKey(
            'fk-fk_remittance_id',
            'dv_aucs'
        );
        $this->dropForeignKey(
            'fk-fk_ro_alphalist_id',
            'dv_aucs'
        );
        // $this->dropIndex('idx-dv_number', 'dv_aucs');
        $this->dropIndex('idx-payee_id', 'dv_aucs');
        $this->dropIndex('idx-book_id', 'dv_aucs');
        $this->dropIndex('idx-payroll_id', 'dv_aucs');
        $this->dropIndex('idx-fk_remittance_id', 'dv_aucs');
        $this->dropIndex('idx-fk_ro_alphalist_id', 'dv_aucs');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230116_090135_add_fk_constraints_in_dv_aucs_table cannot be reverted.\n";

        return false;
    }
    */
}
