<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bac_composition}}`.
 */
class m220113_023802_create_bac_composition_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bac_composition}}', [
            'id' => $this->primaryKey(),
            'effectivity_date' => $this->date(),
            'expiration_date' => $this->date(),
            'rso_number' => $this->string(),
            'created_at'=>$this->timestamp()->defaultExpression("CURRENT_TIMESTAMP")

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bac_composition}}');
    }
}
