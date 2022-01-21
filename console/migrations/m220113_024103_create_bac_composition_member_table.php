<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bac_composition_member}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%bac_composition}}`
 */
class m220113_024103_create_bac_composition_member_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bac_composition_member}}', [
            'id' => $this->primaryKey(),
            'bac_composition_id' => $this->integer(),
            'employee_id' => $this->bigInteger(),
            'bac_position_id' => $this->integer(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `bac_composition_id`
        $this->createIndex(
            '{{%idx-bac_composition_member-bac_composition_id}}',
            '{{%bac_composition_member}}',
            'bac_composition_id'
        );

        // add foreign key for table `{{%bac_composition}}`
        $this->addForeignKey(
            '{{%fk-bac_composition_member-bac_composition_id}}',
            '{{%bac_composition_member}}',
            'bac_composition_id',
            '{{%bac_composition}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bac_composition}}`
        $this->dropForeignKey(
            '{{%fk-bac_composition_member-bac_composition_id}}',
            '{{%bac_composition_member}}'
        );

        // drops index for column `bac_composition_id`
        $this->dropIndex(
            '{{%idx-bac_composition_member-bac_composition_id}}',
            '{{%bac_composition_member}}'
        );

        $this->dropTable('{{%bac_composition_member}}');
    }
}
