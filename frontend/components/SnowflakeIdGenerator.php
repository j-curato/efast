<?php

namespace app\components;

use yii\base\Component;

class SnowflakeIdGenerator extends Component
{
    private $machineId;
    private $epoch = 1609459200000; // Adjust this to your desired epoch (milliseconds since Jan 1, 2021)
    private $sequence = 0;
    private $sequenceBits = 12;
    private $machineIdBits = 10;
    private $maxSequence;
    private $sequenceShift;
    private $timestampShift;
    private $lastTimestamp;

    public function __construct($machineId)
    {
        $this->machineId = $machineId;
        $this->maxSequence = -1 ^ (-1 << $this->sequenceBits);
        $this->sequenceShift = $this->machineIdBits;
        $this->timestampShift = $this->sequenceBits + $this->machineIdBits;
    }
    public function generateId()
    {
        $currentTimestamp = $this->getCurrentTimestamp();
        if ($currentTimestamp < $this->epoch) {
            throw new \Exception("System clock moved backwards. Refusing to generate Snowflake ID.");
        }

        if ($currentTimestamp === $this->lastTimestamp) {
            $this->sequence = ($this->sequence + 1) & $this->maxSequence;
            if ($this->sequence === 0) {
                $currentTimestamp = $this->waitNextMillis($this->lastTimestamp);
            }
        } else {
            $this->sequence = 0;
        }

        $this->lastTimestamp = $currentTimestamp;

        $id =  ((int)($currentTimestamp - $this->epoch) << (int)$this->timestampShift) |
            ((int)$this->machineId << (int)$this->sequenceShift) |
            (int)$this->sequence;
        return $id;
    }

    private function getCurrentTimestamp()
    {
        return (int)(microtime(true) * 1000);
    }

    private function waitNextMillis($lastTimestamp)
    {
        $timestamp = $this->getCurrentTimestamp();
        while ($timestamp <= $lastTimestamp) {
            $timestamp = $this->getCurrentTimestamp();
        }
        return $timestamp;
    }
}
