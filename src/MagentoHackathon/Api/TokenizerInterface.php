<?php

namespace MagentoHackathon\Api;

interface TokenizerInterface
{
    /**
     * Returns a tokens as array
     * @param string $filename
     * @return array
     */
    public function execute(string $filename): array;
}
