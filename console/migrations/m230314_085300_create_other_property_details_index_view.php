<?php

use yii\db\Migration;

/**
 * Class m230314_085300_create_other_property_details_index_view
 */
class m230314_085300_create_other_property_details_index_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP VIEW IF EXISTS other_property_details_index;
            CREATE VIEW other_property_details_index as 
            SELECT 
            other_property_details.id,
            office.office_name,
            property.property_number,
            property.description,
            chart_of_accounts.uacs,
            chart_of_accounts.general_ledger,
            IFNULL(property_articles.article_name,property.article) as article,
            other_property_details.salvage_value_prcnt,
            other_property_details.useful_life
            FROM other_property_details
            JOIN property ON other_property_details.fk_property_id = property.id
            LEFT JOIN office ON property.fk_office_id = office.id
            LEFT JOIN property_articles ON property.fk_property_article_id = property_articles.id
            LEFT JOIN chart_of_accounts ON other_property_details.fk_chart_of_account_id = chart_of_accounts.id
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
        echo "m230314_085300_create_other_property_details_index_view cannot be reverted.\n";

        return false;
    }
    */
}
