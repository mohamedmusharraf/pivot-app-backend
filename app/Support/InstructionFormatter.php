<?php

namespace App\Support;

class InstructionFormatter
{
    public static function normalize(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $text = trim((string) $value);

        if ($text === '') {
            return '';
        }

        $text = self::unwrapInstructionPair($text);
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;

        $ingredients = self::extractSection($text, 'Ingredients');
        $method = self::extractSection($text, 'Method');
        $adaptation = self::extractSection($text, 'Adaptation note');

        $parts = [];

        if ($ingredients !== null && $ingredients !== '') {
            $items = array_values(array_filter(array_map(
                static fn (string $item): string => trim($item),
                preg_split('/,\s*/', $ingredients) ?: []
            )));

            if (!empty($items)) {
                $parts[] = "Ingredients:\n" . implode("\n", array_map(
                    static fn (string $item): string => '- ' . rtrim($item, '.'),
                    $items
                ));
            }
        }

        if ($method !== null && $method !== '') {
            $parts[] = "Method:\n" . self::formatSteps($method);
        }

        if (($ingredients === null && $method === null) || empty($parts)) {
            $textWithoutAdaptation = self::removeSection($text, 'Adaptation note');
            $parts[] = self::formatSteps($textWithoutAdaptation);
        }

        if ($adaptation !== null && $adaptation !== '') {
            $parts[] = 'Adaptation note: ' . trim($adaptation);
        }

        return implode("\n\n", array_filter($parts, static fn ($part) => trim((string) $part) !== ''));
    }

    private static function unwrapInstructionPair(string $text): string
    {
        if (!preg_match('/^"?(?:instruction|insruction)"?\s*:\s*"(.*)"\s*,?$/i', $text, $matches)) {
            return $text;
        }

        return trim((string) ($matches[1] ?? ''));
    }

    private static function extractSection(string $text, string $section): ?string
    {
        $pattern = '/\b' . preg_quote($section, '/') . ':\s*(.+?)(?=\s*\b(?:Ingredients|Method|Adaptation note):|$)/is';

        if (!preg_match($pattern, $text, $matches)) {
            return null;
        }

        return trim((string) ($matches[1] ?? ''));
    }

    private static function removeSection(string $text, string $section): string
    {
        $pattern = '/\b' . preg_quote($section, '/') . ':\s*(.+?)(?=\s*\b(?:Ingredients|Method|Adaptation note):|$)/is';

        return trim((string) preg_replace($pattern, '', $text));
    }

    private static function formatSteps(string $text): string
    {
        $normalized = trim((string) preg_replace('/\s*(\d+)\.\s*/', "\n$1. ", $text));

        if ($normalized === '') {
            return '';
        }

        $lines = array_values(array_filter(array_map('trim', explode("\n", $normalized))));

        if (empty($lines)) {
            return '';
        }

        return implode("\n", $lines);
    }
}
