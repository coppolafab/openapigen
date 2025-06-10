<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use Iterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

use function file_get_contents;
use function in_array;
use function is_string;
use function ltrim;
use function token_get_all;

use const T_CLASS;
use const T_COMMENT;
use const T_DOC_COMMENT;
use const T_DOUBLE_COLON;
use const T_INTERFACE;
use const T_NAME_QUALIFIED;
use const T_NAMESPACE;
use const T_NS_SEPARATOR;
use const T_STRING;
use const T_TRAIT;
use const T_WHITESPACE;

final readonly class ClassMapBuilder
{
    public static function build(Iterator|string|array $dirs): array
    {
        $map = [];

        if (is_string($dirs) || $dirs instanceof Iterator) {
            $dirs = [$dirs];
        }

        foreach ($dirs as $dir) {
            $iterator = $dir;

            if (is_string($dir)) {
                $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
            }

            foreach ($iterator as $file) {
                if (! $file->isFile() || $file->getExtension() !== 'php') {
                    continue;
                }

                $path = $file->getRealPath() ?: $file->getPathname();
                $definitions = self::findDefinitions($path);

                foreach ($definitions as $definition) {
                    $map[$definition] = $path;
                }
            }
        }

        return $map;
    }

    private static function findDefinitions(string $path): array
    {
        $contents = file_get_contents($path);
        $tokens = token_get_all($contents);
        $nsTokens = [T_STRING => true, T_NS_SEPARATOR => true, T_NAME_QUALIFIED => true];

        $definitions = [];
        $namespace = '';

        for ($i = 0; isset($tokens[$i]); $i++) {
            $token = $tokens[$i];

            if (! isset($token[1])) {
                continue;
            }

            $class = '';

            switch ($token[0]) {
                case T_NAMESPACE:
                    $namespace = '';
                    // If there is a namespace, extract it
                    while (isset($tokens[++$i][1])) {
                        if (! isset($nsTokens[$tokens[$i][0]])) {
                            continue;
                        }

                        $namespace .= $tokens[$i][1];
                    }

                    $namespace .= '\\';

                    break;
                case T_CLASS:
                case T_INTERFACE:
                case T_TRAIT:
                case T_ENUM:
                    // Skip usage of ::class constant
                    $isClassConstant = false;

                    for ($j = $i - 1; $j > 0; $j--) {
                        if (! isset($tokens[$j][1])) {
                            break;
                        }

                        if ($tokens[$j][0] === T_DOUBLE_COLON) {
                            $isClassConstant = true;

                            break;
                        }

                        if (! in_array($tokens[$j][0], [T_WHITESPACE, T_DOC_COMMENT, T_COMMENT], true)) {
                            break;
                        }
                    }

                    if ($isClassConstant) {
                        break;
                    }

                    // Find the class/enum name
                    while (isset($tokens[++$i][1])) {
                        $t = $tokens[$i];

                        if ($t[0] === T_STRING) {
                            $class .= $t[1];
                        } elseif ($class !== '' && $t[0] === T_WHITESPACE) {
                            break;
                        }
                    }

                    $definitions[] = ltrim($namespace . $class, '\\');

                    break;
                default:
                    break;
            }
        }

        return $definitions;
    }
}
