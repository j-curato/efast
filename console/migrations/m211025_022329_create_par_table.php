<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%par}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%property}}`
 */
class m211025_022329_create_par_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%par}}', [
            'par_number' => $this->string(),
            'property_number' => $this->string(),
            'date' => $this->date(),
            'employee_id'=>$this->string(),
            'agency_id'=>$this->integer()
            
            
        ]);
        $this->addPrimaryKey('pk-par-number', 'par', 'par_number');

        // creates index for column `property_number`
        $this->createIndex(
            '{{%idx-par-property_number}}',
            '{{%par}}',
            'property_number'
        );

        // add foreign key for table `{{%property}}`
        $this->addForeignKey(
            '{{%fk-par-property_number}}',
            '{{%par}}',
            'property_number',
            '{{%property}}',
            'property_number',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%property}}`
        $this->dropForeignKey(
            '{{%fk-par-property_number}}',
            '{{%par}}'
        );

        // drops index for column `property_number`
        $this->dropIndex(
            '{{%idx-par-property_number}}',
            '{{%par}}'
        );

        $this->dropTable('{{%par}}');
    }
}
