<?php

declare(strict_types=1);

namespace App\Application\Service\Placeholders;

interface PlaceholderReplacerInterface
{
    /**
     * @param string $template
     * @param array<string> $placeholders
     * @return string
     */
    public function replace(string $template, array $placeholders): string;
}
