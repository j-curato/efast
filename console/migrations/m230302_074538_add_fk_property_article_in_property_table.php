<?php

use yii\db\Migration;

/**
 * Class m230302_074538_add_fk_property_article_in_property_table
 */
class m230302_074538_add_fk_property_article_in_property_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('property', 'fk_property_article_id', $this->integer());
        $this->createIndex('idx-fk_property_article_id', 'property', 'fk_property_article_id');
        $this->addForeignKey('fk-pty-fk_property_article_id', 'property', 'fk_property_article_id', 'property_articles', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-pty-fk_property_article_id', 'property');
        $this->dropIndex('idx-fk_property_article_id', 'property');
        $this->dropColumn('property', 'fk_property_article_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230302_074538_add_fk_property_article_in_property_table cannot be reverted.\n";

        return false;
    }
    */
}
