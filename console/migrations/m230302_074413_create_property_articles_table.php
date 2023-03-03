<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%property_articles}}`.
 */
class m230302_074413_create_property_articles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%property_articles}}', [
            'id' => $this->primaryKey(),
            'article_name' => $this->string()->notNull()->unique(),
            'create_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%property_articles}}');
    }
}
