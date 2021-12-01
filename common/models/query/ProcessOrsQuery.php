<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\ProcessOrs]].
 *
 * @see \common\models\ProcessOrs
 */
class ProcessOrsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\ProcessOrs[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\ProcessOrs|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
