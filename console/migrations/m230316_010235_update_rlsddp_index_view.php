<?php

use yii\db\Migration;

/**
 * Class m230316_010235_update_rlsddp_index_view
 */
class m230316_010235_update_rlsddp_index_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
        DROP VIEW IF EXISTS rlsddp_index;
        CREATE VIEW rlsddp_index AS 
        SELECT
            CONCAT(actbl_ofr.f_name,' ',
            (CASE
            WHEN actbl_ofr.m_name !='' THEN CONCAT(LEFT(actbl_ofr.m_name,1),'. ')
            ELSE ''
            END),
            actbl_ofr.l_name,
            (CASE
            WHEN actbl_ofr.suffix !='' THEN CONCAT(', ',actbl_ofr.suffix)
            ELSE ''
            END) 
            ) as accountable_officer,

            CONCAT(spvr.f_name,' ',
            (CASE
            WHEN spvr.m_name !='' THEN CONCAT(LEFT(spvr.m_name,1),'. ')
            ELSE ''
            END),
            spvr.l_name,
            (CASE
            WHEN spvr.suffix !='' THEN CONCAT(', ',spvr.suffix)
            ELSE ''
            END) 
            ) as supervisor,
            rlsddp.id,
            rlsddp.serial_number,
            rlsddp.date,
            rlsddp.blotter_date,
            rlsddp.circumstances,
            rlsddp.police_station,
            (CASE 
            WHEN rlsddp.is_blottered = 1 THEN 'YES'
            ELSE 'NO'
            END) as blottered,
            office.office_name,
            (CASE
                WHEN rlsddp.`status` =1 THEN 'Lost'
                WHEN rlsddp.`status` =2 THEN 'Stolen'
                WHEN rlsddp.`status` =3 THEN 'Damage'
                WHEN rlsddp.`status` =4 THEN 'Destroyed'
                END) as `status`
            FROM `rlsddp`
            LEFT JOIN  employee as actbl_ofr ON rlsddp.fk_acctbl_offr = actbl_ofr.employee_id
            LEFT JOIN  employee as spvr ON rlsddp.fk_supvr = spvr.employee_id
            LEFT JOIN office ON rlsddp.fk_office_id = office.id
           
           
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
        echo "m230316_010235_update_rlsddp_index_view cannot be reverted.\n";

        return false;
    }
    */
}
