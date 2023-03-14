<?php

use yii\db\Migration;

/**
 * Class m230313_050440_create_property_card_index_view
 */
class m230313_050440_create_property_card_index_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP VIEW IF EXISTS property_card_index;
            CREATE VIEW property_card_index as 
                SELECT 
                property_card.id,
                property_card.serial_number as pc_number,
                par.par_number,
                par.date as par_date,
                received_by.employee_name as rcv_by,
                actual_user.employee_name as act_usr,
                issued_by.employee_name as isd_by,
                location.location,
                property.property_number,
                property.date as acquisition_date,
                property.acquisition_amount,
                property.description,
                property.serial_number,
                unit_of_measure.unit_of_measure,
                IFNULL(property_articles.article_name,property.article) as article,
                (CASE
                WHEN par.is_unserviceable =1 THEN 'UnServiceable'
                ELSE 'Serviceable' 
                END ) as is_unserviceable,
                office.office_name
                FROM property_card
                JOIN  par ON property_card.fk_par_id = par.id
                JOIN property ON par.fk_property_id = property.id
                LEFT JOIN employee_search_view as received_by ON par.fk_received_by = received_by.employee_id
                LEFT JOIN employee_search_view as actual_user ON par.fk_actual_user = actual_user.employee_id
                LEFT JOIN employee_search_view as issued_by ON par.fk_issued_by_id = issued_by.employee_id
                LEFT JOIN location ON par.fk_location_id = location.id
                LEFT JOIN office ON par.fk_office_id = office.id
                LEFT JOIN unit_of_measure ON property.unit_of_measure_id = unit_of_measure.id
                LEFT JOIN property_articles ON property.fk_property_article_id = property_articles.id
                ORDER BY par.par_number DESC 
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
        echo "m230313_050440_create_property_card_index_view cannot be reverted.\n";

        return false;
    }
    */
}
