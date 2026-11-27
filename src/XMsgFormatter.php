<?php

declare(strict_types=1);


namespace pvc\err;

final class XMsgFormatter
{
    /**
     * Formats templates like "Gateway {gateway:%s} timed out after {ms:%d} ms (fee: {fee:%.2f})."
     *
     * Allowed specifiers: %s, %d, %.Nf (or omitted, which defaults to %s).
     *
     * @param  array<string, string|float|int>  $context
     *
     * @throws \InvalidArgumentException When placeholders are malformed, keys are missing, or parameter counts mismatch.
     */
    public static function format(string $template, array $context): string
    {
        // Reject malformed placeholders (e.g., disallowed specifiers like %x, %f without precision, or %1$s)
        if (preg_match(
                '/\{[a-zA-Z_][a-zA-Z0-9_]*:(?!%(?:s|d|\.\d+f)\})[^}]*\}/',
                $template
            ) === 1
        ) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Template "%s" contains an invalid or unsupported format specifier. Only %%s, %%d, and %%.Nf are allowed.',
                    $template
                )
            );
        }

        $orderedValues = [];
        $usedKeys = [];

        // Matches {paramName} or {paramName:%s|%d|%.Nf}
        $compiledTemplate = preg_replace_callback(
            '/\{([a-zA-Z_][a-zA-Z0-9_]*)(?::(%(?:s|d|\.\d+f)))?\}/',
            static function (array $matches) use (
                $context,
                &$orderedValues,
                &
                $usedKeys
            ): string {
                $key = $matches;
                $specifier = $matches ?? '%s';

                if (!array_key_exists($key, $context)) {
                    throw new \InvalidArgumentException(
                        sprintf(
                            'Missing required exception message parameter "{%s}".',
                            $key
                        )
                    );
                }

                $orderedValues[] = $context[$key];
                $usedKeys[$key] = true;

                return $specifier;
            },
            $template
        );

        if ($compiledTemplate === null) {
            throw new \RuntimeException(
                'Failed to parse exception message template.'
            );
        }

        // Enforce exact 1-to-1 count between unique template placeholders and provided context parameters
        if (count($usedKeys) !== count($context)) {
            $unusedKeys = array_diff(
                array_keys($context),
                array_keys($usedKeys)
            );
            throw new \InvalidArgumentException(
                sprintf(
                    'Parameter count mismatch: template expects %d parameter(s), got %d. Unused parameter(s): %s.',
                    count($usedKeys),
                    count($context),
                    implode(', ', $unusedKeys)
                )
            );
        }

        if ($orderedValues === []) {
            return $template;
        }

        return vsprintf($compiledTemplate, $orderedValues);
    }
}
