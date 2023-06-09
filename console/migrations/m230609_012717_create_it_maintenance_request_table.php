<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%it_maintenance_request}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%employee}}`
 * - `{{%employee}}`
 * - `{{%divisions}}`
 */
class m230609_012717_create_it_maintenance_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%it_maintenance_request}}', [
            'id' => $this->primaryKey(),
            'fk_requested_by' => $this->bigInteger(),
            'fk_worked_by' => $this->bigInteger(),
            'fk_division_id' => $this->bigInteger(),
            'serial_number' => $this->string()->unique()->notNull(),
            'date_requested' => $this->date()->notNull(),
            'date_accomplished' => $this->date(),
            'description' => $this->text()->notNull(),
            'action_taken' => $this->text()->notNull(),
            'type' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('it_maintenance_request', 'id', $this->bigInteger());
        // creates index for column `fk_requested_by`
        $this->createIndex(
            '{{%idx-it_maintenance_request-fk_requested_by}}',
            '{{%it_maintenance_request}}',
            'fk_requested_by'
        );

        // add foreign key for table `{{%employee}}`
        $this->addForeignKey(
            '{{%fk-it_maintenance_request-fk_requested_by}}',
            '{{%it_maintenance_request}}',
            'fk_requested_by',
            '{{%employee}}',
            'employee_id',
            'RESTRICT'
        );

        // creates index for column `fk_worked_by`
        $this->createIndex(
            '{{%idx-it_maintenance_request-fk_worked_by}}',
            '{{%it_maintenance_request}}',
            'fk_worked_by'
        );

        // add foreign key for table `{{%employee}}`
        $this->addForeignKey(
            '{{%fk-it_maintenance_request-fk_worked_by}}',
            '{{%it_maintenance_request}}',
            'fk_worked_by',
            '{{%employee}}',
            'employee_id',
            'RESTRICT'
        );

        // creates index for column `fk_division_id`
        $this->createIndex(
            '{{%idx-it_maintenance_request-fk_division_id}}',
            '{{%it_maintenance_request}}',
            'fk_division_id'
        );

        // add foreign key for table `{{%divisions}}`
        $this->addForeignKey(
            '{{%fk-it_maintenance_request-fk_division_id}}',
            '{{%it_maintenance_request}}',
            'fk_division_id',
            '{{%divisions}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%employee}}`
        $this->dropForeignKey(
            '{{%fk-it_maintenance_request-fk_requested_by}}',
            '{{%it_maintenance_request}}'
        );

        // drops index for column `fk_requested_by`
        $this->dropIndex(
            '{{%idx-it_maintenance_request-fk_requested_by}}',
            '{{%it_maintenance_request}}'
        );

        // drops foreign key for table `{{%employee}}`
        $this->dropForeignKey(
            '{{%fk-it_maintenance_request-fk_worked_by}}',
            '{{%it_maintenance_request}}'
        );

        // drops index for column `fk_worked_by`
        $this->dropIndex(
            '{{%idx-it_maintenance_request-fk_worked_by}}',
            '{{%it_maintenance_request}}'
        );

        // drops foreign key for table `{{%divisions}}`
        $this->dropForeignKey(
            '{{%fk-it_maintenance_request-fk_division_id}}',
            '{{%it_maintenance_request}}'
        );

        // drops index for column `fk_division_id`
        $this->dropIndex(
            '{{%idx-it_maintenance_request-fk_division_id}}',
            '{{%it_maintenance_request}}'
        );

        $this->dropTable('{{%it_maintenance_request}}');
    }
}
