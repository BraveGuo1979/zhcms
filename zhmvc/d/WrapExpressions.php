<?php
namespace ZHMVC\D;

class WrapExpressions extends \ZHMVC\D\DataBase{
    public function __toString() {
        return ($this->start ? $this->start: '('). implode(($this->delimiter ? $this->delimiter: ','), $this->target). ($this->end?$this->end:')');
    }
}
