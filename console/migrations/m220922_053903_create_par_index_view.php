<?php

use yii\db\Migration;

/**
 * Class m220922_053903_create_par_index_view
 */
class m220922_053903_create_par_index_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand(
            "DROP VIEW IF EXISTS par_index;
        CREATE VIEW par_index as 
        SELECT 
        par.id,
        par.par_number,
        par.date,
        property.property_number,
        actual_user.employee_name as actual_user,
        recieved_by.employee_name as recieved_by,
        unit_of_measure.unit_of_measure,
        books.`name` as book_name,
		property.article,
		REPLACE(property.description,'[n]','\n') as description,
        property.iar_number,
        property.acquisition_amount
        FROM par
        LEFT JOIN property ON par.fk_property_id = property.id
        LEFT JOIN employee_search_view as actual_user ON par.actual_user = actual_user.employee_id
        LEFT JOIN employee_search_view as recieved_by ON par.employee_id = recieved_by.employee_id
        LEFT JOIN books ON property.book_id = books.id
        LEFT JOIN unit_of_measure ON property.unit_of_measure_id = unit_of_measure"
        )
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
        echo "m220922_053903_create_par_index_view cannot be reverted.\n";

        return false;
    }
    */
}
