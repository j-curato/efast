<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cibr}}`.
 */
class m210712_014629_create_cibr_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cibr}}', [
            'id' => $this->primaryKey(),
            'reporting_period'=>$this->string(50),
            'province'=>$this->string(),
            'book_name'=>$this->string()
        ]);
    }

    /**p
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cibr}}');
    }
}
