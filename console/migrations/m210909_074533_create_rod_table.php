<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rod}}`.
 */
class m210909_074533_create_rod_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%rod}}', [
            'rod_number' => $this->string(),
            'province'=>$this->string()
        ]);
        $this->addPrimaryKey('primary-key','rod','rod_number');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%rod}}');
    }
}
