<?php

namespace Jarvis\Framework\Common;

interface Serializable
{
    /**
     * Serialize object
     *
     * @return array
     */
    public function serialize(): array;
}
