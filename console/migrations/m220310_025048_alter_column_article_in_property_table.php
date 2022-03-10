<?php

use yii\db\Migration;

/**
 * Class m220310_025048_alter_column_article_in_property_table
 */
class m220310_025048_alter_column_article_in_property_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('property', 'article', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('property', 'article', $this->text());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220310_025048_alter_column_article_in_property_table cannot be reverted.\n";

        return false;
    }
    */
}
