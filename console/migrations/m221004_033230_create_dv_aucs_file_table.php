<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dv_aucs_file}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%dv_aucs}}`
 */
class m221004_033230_create_dv_aucs_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dv_aucs_file}}', [
            'id' => $this->primaryKey(),
            'fk_dv_aucs_id' => $this->integer(),
            'file_name' => $this->text(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `fk_dv_aucs_id`
        $this->createIndex(
            '{{%idx-dv_aucs_file-fk_dv_aucs_id}}',
            '{{%dv_aucs_file}}',
            'fk_dv_aucs_id'
        );

        // add foreign key for table `{{%dv_aucs}}`
        $this->addForeignKey(
            '{{%fk-dv_aucs_file-fk_dv_aucs_id}}',
            '{{%dv_aucs_file}}',
            'fk_dv_aucs_id',
            '{{%dv_aucs}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%dv_aucs}}`
        $this->dropForeignKey(
            '{{%fk-dv_aucs_file-fk_dv_aucs_id}}',
            '{{%dv_aucs_file}}'
        );

        // drops index for column `fk_dv_aucs_id`
        $this->dropIndex(
            '{{%idx-dv_aucs_file-fk_dv_aucs_id}}',
            '{{%dv_aucs_file}}'
        );

        $this->dropTable('{{%dv_aucs_file}}');
    }
}
