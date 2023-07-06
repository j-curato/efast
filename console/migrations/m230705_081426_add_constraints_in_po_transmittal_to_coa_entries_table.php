<?php

use yii\db\Migration;

/**
 * Class m230705_081426_add_constraints_in_po_transmittal_to_coa_entries_table
 */
class m230705_081426_add_constraints_in_po_transmittal_to_coa_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 0")->query();
        $this->createIndex('idx-tmtl-coa-fk_po_transmittal_to_coa_id', 'po_transmittal_to_coa_entries', 'fk_po_transmittal_to_coa_id');
        $this->addForeignKey('fk-tmtl-coa-fk_po_transmittal_to_coa_id', 'po_transmittal_to_coa_entries', 'fk_po_transmittal_to_coa_id', 'po_transmittal_to_coa', 'id', 'CASCADE');
        $this->createIndex('idx-tmtl-coa-fk_po_transmittal_id', 'po_transmittal_to_coa_entries', 'fk_po_transmittal_id');
        $this->addForeignKey('fk-tmtl-coa-fk_po_transmittal_id', 'po_transmittal_to_coa_entries', 'fk_po_transmittal_id', 'po_transmittal', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-tmtl-coa-fk_po_transmittal_to_coa_id', 'po_transmittal_to_coa_entries');
        $this->dropIndex('idx-tmtl-coa-fk_po_transmittal_to_coa_id', 'po_transmittal_to_coa_entries');
        $this->dropForeignKey('fk-tmtl-coa-fk_po_transmittal_id', 'po_transmittal_to_coa_entries');
        $this->dropIndex('idx-tmtl-coa-fk_po_transmittal_id', 'po_transmittal_to_coa_entries');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230705_081426_add_constraints_in_po_transmittal_to_coa_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
