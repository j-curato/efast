<?php

use yii\db\Migration;

/**
 * Class m230411_053040_create_detailed_property_database_view
 */
class m230411_053040_create_detailed_property_database_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS detailed_property_database;
CREATE VIEW detailed_property_database as 
WITH par_details as (
                    SELECT 
                    par.par_number,
                    par.date as par_date,
                    received_by.employee_name as rcv_by,
                    actual_user.employee_name as act_usr,
                    issued_by.employee_name as isd_by,
                    location.location,
                    par.fk_property_id,
                    property_card.serial_number as pc_num,
                    office.office_name,
                    divisions.division,
                    par.is_current_user,
                    (CASE WHEN par.is_current_user =1 THEN 'Current User'
                    ELSE 'Not Current User'
                    END
                    ) as isCrntUsr,
                    (CASE WHEN par.is_unserviceable =1 THEN 'UnServiceable'
                    ELSE 'Serviceable'
                    END
                    ) as isUnserviceable
                    
                    FROM 
                    par
                    LEFT JOIN employee_search_view as received_by ON par.fk_received_by = received_by.employee_id
                    LEFT JOIN employee_search_view as actual_user ON par.fk_actual_user = actual_user.employee_id
                    LEFT JOIN employee_search_view as issued_by ON par.fk_issued_by_id = issued_by.employee_id
                    LEFT JOIN location ON par.fk_location_id = location.id
                    LEFT JOIN property_card ON par.id = property_card.fk_par_id
                    LEFT JOIN office ON location.fk_office_id = office.id
                    LEFT JOIN divisions ON location.fk_division_id = divisions.id
                )
                
                
                SELECT 
                property.id as property_id,
                 par_details.pc_num,
                ptr.ptr_number,
                ptr.date as ptr_date,
                transfer_type.`type`,
                derecognition.serial_number as derecognition_num,
                derecognition.date as derecognition_date,
                property.property_number,
                property.date as date_acquired,
                property.serial_number,
                IFNULL(property_articles.article_name,property.article) as article,
                property.description,
                property.acquisition_amount,
                unit_of_measure.unit_of_measure,
                other_property_details.useful_life,
                (CASE
                    WHEN DAY(property.date) > 15 THEN DATE_FORMAT(  DATE_ADD( property.date,INTERVAL 1 MONTH), '%Y-%m')
                    ELSE DATE_FORMAT(property.date, '%Y-%m')
                END ) as strt_mnth,
                DATE_FORMAT(
                    DATE_ADD(
                        (CASE
                            WHEN DAY(property.date) > 15 THEN DATE_FORMAT(  DATE_ADD( property.date,INTERVAL 1 MONTH), '%Y-%m-01')
                            ELSE DATE_FORMAT(property.date, '%Y-%m-01')
                        END),INTERVAL other_property_details.useful_life-1 MONTH
                    ), '%Y-%m'
                ) as lst_mth,
                (CASE
					WHEN derecognition.date IS NOT NULL THEN DATE_FORMAT(derecognition.date,'%Y-%m') 
					ELSE
									DATE_FORMAT(
									DATE_ADD(
									(CASE
										WHEN DAY(property.date) > 15 THEN DATE_FORMAT(  DATE_ADD( property.date,INTERVAL 1 MONTH), '%Y-%m-01')
										ELSE DATE_FORMAT(property.date, '%Y-%m-01')
										END),INTERVAL other_property_details.useful_life -1 MONTH
										), '%Y-%m'
										)
									END) as new_last_month,
                
                                    DATE_FORMAT(
                    DATE_ADD(
                        (CASE
                            WHEN DAY(property.date) > 15 THEN DATE_FORMAT(  DATE_ADD( property.date,INTERVAL 1 MONTH), '%Y-%m-01')
                            ELSE DATE_FORMAT(property.date, '%Y-%m-01')
                        END),INTERVAL other_property_details.useful_life -2 MONTH
                    ), '%Y-%m'
                ) as sec_lst_mth,
                par_details.par_number,
                par_details.par_date ,
                par_details.rcv_by,
                par_details.act_usr,
                par_details.isd_by,
                par_details.office_name,
                par_details.division,
                par_details.location,
                par_details.isCrntUsr,
                par_details.isUnserviceable,
                par_details.is_current_user,
                chart_of_accounts.uacs,
                chart_of_accounts.general_ledger,
                depreciation_sub_account.`name` as depreciation_account_title,
                depreciation_sub_account.`object_code` as depreciation_object_code
                
                FROM property
                LEFT JOIN ptr ON property.id = ptr.fk_property_id
                LEFT JOIN unit_of_measure ON property.unit_of_measure_id = unit_of_measure.id
                LEFT JOIN property_articles ON property.fk_property_article_id = property_articles.id
                LEFT JOIN other_property_details ON property.id = other_property_details.fk_property_id
                LEFT JOIN par_details ON property.id = par_details.fk_property_id
                LEFT JOIN sub_accounts1 ON other_property_details.fk_sub_account1_id = sub_accounts1.id
                LEFT JOIN sub_accounts1 as depreciation_sub_account ON other_property_details.fk_depreciation_sub_account1_id = depreciation_sub_account.id
                LEFT JOIN derecognition ON property.id = derecognition.fk_property_id
                LEFT JOIN transfer_type ON ptr.fk_transfer_type_id = transfer_type.id
                LEFT JOIN chart_of_accounts ON other_property_details.fk_chart_of_account_id  = chart_of_accounts.id

                ORDER BY property.property_number DESC")->query();
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
        echo "m230411_053040_create_detailed_property_database_view cannot be reverted.\n";

        return false;
    }
    */
}
