<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%jev_preparation}}`.
 */
class m210316_011352_drop_entity_name_column_from_jev_preparation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('jev_preparation','entity_name');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()

    {
        $this->addColumn('jev_preparation','entity_name',$this->string());
    }
}
