<?php

use yii\db\Migration;

/**
 * Class m230711_065733_create_vw_jev_preparation_index_view
 */
class m230711_065733_create_vw_jev_preparation_index_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        YIi::$app->db->createCommand("DROP VIEW IF EXISTS vw_jev_preparation_index_view;
        CREATE VIEW vw_jev_preparation_index_view AS SELECT 

        jev_preparation.id,
        jev_preparation.jev_number,
        jev_preparation.date,
        jev_preparation.reporting_period,
        jev_preparation.entry_type,
        jev_preparation.ref_number as reference_type,
        responsibility_center.`name` as res_center,
        books.`name` book_name,
        payee.account_name as payee,
        jev_preparation.check_ada,
        jev_preparation.explaination,
        dv_aucs.dv_number
        FROM 
        jev_preparation
        LEFT JOIN responsibility_center ON jev_preparation.responsibility_center_id = responsibility_center.id
        LEFT JOIN books ON jev_preparation.book_id = books.id
        LEFT JOIN dv_aucs ON jev_preparation.fk_dv_aucs_id = dv_aucs.id
        LEFT JOIN payee ON jev_preparation.payee_id = payee.id")
            ->query();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230711_065733_create_vw_jev_preparation_index_view cannot be reverted.\n";

        return false;
    }
    */
}
