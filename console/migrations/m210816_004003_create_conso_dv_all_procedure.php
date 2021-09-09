<?php

use yii\db\Migration;

/**
 * Class m210711_092752_create_conso_dv_all_procedure
 */
class m210816_004003_create_conso_dv_all_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

            $createTriggerSql = <<< SQL
                CREATE PROCEDURE conso_dv_all (r_year VARCHAR(20)) 
                BEGIN

                SELECT
                detailed_dv_aucs.mfo_code,
                detailed_dv_aucs.mfo_name,
                detailed_dv_aucs.mfo_description,
                r_allotment.total_allotment as total_allotment_recieve,
                t_obligation.total as conso_total_obligation,
                SUM(detailed_dv_aucs.dv_ewt) as conso_total_ewt,
                SUM(detailed_dv_aucs.dv_amount) as conso_total_dv,
                SUM(detailed_dv_aucs.dv_vat) as conso_total_vat
                FROM detailed_dv_aucs
                    LEFT JOIN(
                SELECT 


                SUM(record_allotment_entries.amount) as total_allotment,


                mfo_pap_code.`code` as mfo_code
                FROM record_allotments,record_allotment_entries,chart_of_accounts,major_accounts,
                mfo_pap_code
                WHERE 
                record_allotments.id = record_allotment_entries.record_allotment_id
                AND record_allotment_entries.chart_of_account_id = chart_of_accounts.id
                AND chart_of_accounts.major_account_id =major_accounts.id
                AND record_allotments.mfo_pap_code_id = mfo_pap_code.id
                            
                                AND record_allotments.reporting_period  LIKE  r_year

                GROUP BY mfo_pap_code.`name`




                ) as r_allotment ON detailed_dv_aucs.mfo_code = r_allotment.mfo_code

                LEFT JOIN(
                SELECT 
                mfo_pap_code.`code` as mfo_code,
                SUM(raoud_entries.amount) as total

                FROM process_ors,raouds,raoud_entries,record_allotment_entries,chart_of_accounts,major_accounts,
                record_allotments,mfo_pap_code

                WHERE process_ors.id = raouds.process_ors_id
                AND raouds.id = raoud_entries.raoud_id
                AND raouds.record_allotment_entries_id = record_allotment_entries.id
                AND record_allotment_entries.chart_of_account_id = chart_of_accounts.id
                AND chart_of_accounts.major_account_id = major_accounts.id
                AND record_allotment_entries.record_allotment_id = record_allotments.id
                AND record_allotments.mfo_pap_code_id = mfo_pap_code.`id`
                AND	process_ors.reporting_period  LIKE r_year
                GROUP BY mfo_pap_code.`code`


                                )as t_obligation ON detailed_dv_aucs.mfo_code = t_obligation.mfo_code
                WHERE detailed_dv_aucs.check_or_ada_no IS NOT NULL

                AND detailed_dv_aucs.reporting_period  LIKE  r_year
                AND detailed_dv_aucs.mfo_code IS NOT NULL
                GROUP BY detailed_dv_aucs.mfo_code 

                UNION

                SELECT 
                ''as mfo_code,

                'No ORS'as mfo_name,
                mrd_name as mfo_description,
                0 as total_allotment_recieve,
                0 as conso_total_obligation,
                SUM(total_ewt) as conso_total_ewt,
                SUM(total_dv) as conso_total_dv,
                    SUM(total_vat) as conso_total_vat
                FROM `detailed_dv_aucs` WHERE detailed_dv_aucs.allotment_class IS NULL
                AND  detailed_dv_aucs.reporting_period LIKE r_year
                GROUP BY mrd_name;
                END


            SQL;
            $this->execute($createTriggerSql);
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        Yii::$app->db->createCommand("DROP PROCEDURE IF EXISTS conso_dv_all")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210711_092752_create_conso_dv_all_procedure cannot be reverted.\n";

        return false;
    }
    */
}
