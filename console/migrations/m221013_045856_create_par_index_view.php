<?php

use yii\db\Migration;

/**
 * Class m221013_045856_create_par_index_view
 */
class m221013_045856_create_par_index_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS par_index;
        CREATE VIEW par_index as SELECT 
        par.id,
        par.par_number,
        property.property_number,
        property.article,
        property.description,
        property.province,
        property.acquisition_amount,
        par.date,
        unit_of_measure.unit_of_measure,
        par.location,
                CASE
                    WHEN par.employee_id IS NULL OR par.employee_id = '' THEN par.accountable_officer
                        ELSE account_officer.employee_name		
                END as accountable_officer,
                CASE
                    WHEN par.actual_user IS NULL OR par.actual_user = '' THEN par.recieve_by_jocos
                        ELSE act_user.employee_name		
                END as actual_user,
                
                CASE
                    WHEN property.employee_id IS NULL OR property.employee_id = '' THEN par.issued_by
                        ELSE property_officer.employee_name		
                END as issued_by,
                
                par.remarks
         FROM par
        LEFT JOIN property ON par.fk_property_id = property.id
                LEFT JOIN employee_search_view  as account_officer ON par.employee_id = account_officer.employee_id
                LEFT JOIN employee_search_view as  act_user ON par.actual_user = act_user.employee_id
                LEFT JOIN employee_search_view as property_officer ON property.employee_id  = property_officer.employee_id
                LEFT JOIN unit_of_measure ON property.unit_of_measure_id = unit_of_measure.id
        
        ")
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
        echo "m221013_045856_create_par_index_view cannot be reverted.\n";

        return false;
    }
    */
}
