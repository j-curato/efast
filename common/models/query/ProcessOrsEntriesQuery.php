<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\ProcessOrsEntries]].
 *
 * @see \common\models\ProcessOrsEntries
 */
class ProcessOrsEntriesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\ProcessOrsEntries[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\ProcessOrsEntries|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
