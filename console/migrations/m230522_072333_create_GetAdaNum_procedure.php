<?php

use yii\db\Migration;

/**
 * Class m230522_072333_create_GetAdaNum_procedure
 */
class m230522_072333_create_GetAdaNum_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
           DROP PROCEDURE IF EXISTS GetAdaNum;

            CREATE PROCEDURE GetAdaNum(yr INT)
            BEGIN
            WITH
                RECURSIVE number_sequence AS (
                SELECT 1 AS number
                UNION ALL
                SELECT number + 1
                FROM number_sequence
                WHERE number < (SELECT CAST(SUBSTRING_INDEX(cash_disbursement.ada_number,'-',-1) AS UNSIGNED) as lst_ada FROM cash_disbursement
                WHERE 
                cash_disbursement.ada_number LIKE '2023%'
                ORDER BY  lst_ada DESC LIMIT 1)
                ),
                adaNums as (
                SELECT number
                FROM number_sequence
                )
                SELECT adaNums.number
                FROM adaNums
                LEFT JOIN (SELECT CAST(SUBSTRING_INDEX(cash_disbursement.ada_number,'-',-1) AS UNSIGNED) as ada_num FROM cash_disbursement
                WHERE 
                cash_disbursement.ada_number LIKE '2023%') as cash_ada_nums ON adaNums.number = cash_ada_nums.ada_num
                WHERE cash_ada_nums.ada_num IS NULL
                ORDER BY adaNums.number 
                LIMIT 1;
            END;
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
        echo "m230522_072333_create_GetAdaNum_procedure cannot be reverted.\n";

        return false;
    }
    */
}
