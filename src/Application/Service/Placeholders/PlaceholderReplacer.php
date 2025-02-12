<?php

declare(strict_types=1);

namespace App\Application\Service\Placeholders;

use App\Application\Exceptions\Event\InvalidPlaceholderCountException;

class PlaceholderReplacer implements PlaceholderReplacerInterface
{
    /**
     * @param string $template
     * @param array<string> $placeholders
     * @return string
     * @throws InvalidPlaceholderCountException
     */
    public function replace(string $template, array $placeholders): string
    {
        preg_match_all('/\{(\w+)\}/', $template, $matches);
        $placeholderKeys = $matches[1];

        if (count($placeholderKeys) !== count($placeholders)) {
            throw new InvalidPlaceholderCountException(sprintf("Invalid number of placeholders. Expected %d, got %d", count($placeholderKeys), count($placeholders)));
        }

        foreach ($placeholders as $key => $value) {
            $template = preg_replace('/\{' . $key . '\}/', $value, $template);
        }
        return $template;
    }
}
