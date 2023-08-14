<?php

use yii\db\Migration;

/**
 * Class m230814_070926_update_employee_search_view
 */
class m230814_070926_update_employee_search_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<< SQL
            DROP VIEW IF EXISTS employee_search_view ;
            CREATE VIEW employee_search_view AS 
                SELECT 
                employee_id ,
                CONCAT(f_name,' ',
                (CASE
                WHEN m_name !='' THEN CONCAT(LEFT(m_name,1),'. ')
                ELSE ''
                END),
                l_name,
                (CASE
                WHEN employee.suffix !='' THEN CONCAT(', ',employee.suffix)
                ELSE ''
                END)
                ) as `employee_name`,
                employee.position,
                employee.property_custodian,
                office.office_name,
                divisions.division

                FROM 
                employee 
                LEFT JOIN office ON employee.fk_office_id = office.id 
                LEFT JOIN divisions ON employee.fk_division_id = divisions.id
                WHERE 
                employee.is_disabled = 0 
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
        echo "m230814_070926_update_employee_search_view cannot be reverted.\n";

        return false;
    }
    */
}
