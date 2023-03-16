<?php

use yii\db\Migration;

/**
 * Class m230316_083807_create_iirup_index_view
 */
class m230316_083807_create_iirup_index_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP VIEW IF EXISTS iirup_index;
            CREATE VIEW iirup_index as 
            SELECT 
            iirup.id,
            iirup.serial_number,
            office.office_name,
            apv_by.employee_name as approved_by,
            acctbl_ofr.employee_name as accountable_officer
            FROM iirup
            LEFT JOIN office ON iirup.fk_office_id = office.id
            LEFT JOIN employee_search_view as apv_by ON iirup.fk_approved_by = apv_by.employee_id
            LEFT JOIN employee_search_view as acctbl_ofr ON iirup.fk_acctbl_ofr = acctbl_ofr.employee_id


        SQL;
        $this->execute($sql);
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
        echo "m230316_083807_create_iirup_index_view cannot be reverted.\n";

        return false;
    }
    */
}
