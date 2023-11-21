<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tbl_mg_liquidations}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%mgrfrs}}`
 */
class m231120_072003_create_tbl_mg_liquidations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tbl_mg_liquidations}}', [
            'id' => $this->primaryKey(),
            'serial_number' => $this->string()->unique()->notNull(),
            'reporting_period' => $this->string()->notNull(),
            'fk_mgrfr_id' => $this->bigInteger()->notNull(),
            'fk_office_id' => $this->integer(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->alterColumn('tbl_mg_liquidations', 'id', $this->bigInteger());
        // creates index for column `fk_mgrfr_id`
        $this->createIndex(
            '{{%idx-tbl_mg_liquidations-fk_mgrfr_id}}',
            '{{%tbl_mg_liquidations}}',
            'fk_mgrfr_id'
        );

        // add foreign key for table `{{%mgrfrs}}`
        $this->addForeignKey(
            '{{%fk-tbl_mg_liquidations-fk_mgrfr_id}}',
            '{{%tbl_mg_liquidations}}',
            'fk_mgrfr_id',
            '{{%mgrfrs}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `fk_office_id`
        $this->createIndex(
            '{{%idx-tbl_mg_liquidations-fk_office_id}}',
            '{{%tbl_mg_liquidations}}',
            'fk_office_id'
        );

        // add foreign key for table `{{%office}}`
        $this->addForeignKey(
            '{{%fk-tbl_mg_liquidations-fk_office_id}}',
            '{{%tbl_mg_liquidations}}',
            'fk_office_id',
            '{{%office}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%mgrfrs}}`
        $this->dropForeignKey(
            '{{%fk-tbl_mg_liquidations-fk_mgrfr_id}}',
            '{{%tbl_mg_liquidations}}'
        );

        // drops index for column `fk_mgrfr_id`
        $this->dropIndex(
            '{{%idx-tbl_mg_liquidations-fk_mgrfr_id}}',
            '{{%tbl_mg_liquidations}}'
        );

        // drop foreign key for table `{{%office}}`
        $this->dropForeignKey(
            '{{%fk-tbl_mg_liquidations-fk_office_id}}',
            '{{%tbl_mg_liquidations}}'

        );
        // drops index for column `fk_office_id`
        $this->dropIndex(
            '{{%idx-tbl_mg_liquidations-fk_office_id}}',
            '{{%tbl_mg_liquidations}}'
        );


        $this->dropTable('{{%tbl_mg_liquidations}}');
    }
}
