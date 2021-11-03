<?php

use yii\db\Migration;

/**
 * Class m211103_044701_create_employee_search_view
 */
class m211103_044701_create_employee_search_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<< SQL
            DROP VIEW IF EXISTS employee_search_view;
            CREATE VIEW employee_search_view as 
            SELECT 
            employee_id ,
            CONCAT(f_name,' ',LEFT(m_name,1),'. ' , l_name) as `employee_name`
            FROM 
            employee 
        SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db - createCommand("DROP VIEW IF EXISTS employee_search_view")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211103_044701_create_employee_search_view cannot be reverted.\n";

        return false;
    }
    */
}
