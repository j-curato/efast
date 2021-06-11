<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%check_range}}`.
 */
class m210611_055328_create_check_range_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%check_range}}', [
            'id' => $this->primaryKey(),
            'from'=>$this->integer(15),
            'to'=>$this->integer(15),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%check_range}}');
    }
}
