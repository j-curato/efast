<?php

use yii\db\Migration;

/**
 * Class m230323_064415_create_derecognition_index_view
 */
class m230323_064415_create_derecognition_index_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS derecognition_index;
        CREATE VIEW derecognition_index as 
        SELECT 
derecognition.id,
derecognition.serial_number,
derecognition.date as derecognition_date,
property.property_number,
IFNULL(property_articles.article_name,property.article) as article,
property.description
FROM derecognition
JOIN property ON derecognition.fk_property_id = property.id
LEFT JOIN property_articles ON property.fk_property_article_id = property_articles.id")
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
        echo "m230323_064415_create_derecognition_index_view cannot be reverted.\n";

        return false;
    }
    */
}
