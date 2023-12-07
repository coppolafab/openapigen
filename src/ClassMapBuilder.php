<?php

declare(strict_types=1);

namespace Coppolafab\OpenApi;

use Iterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

use function defined;
use function file_get_contents;
use function in_array;
use function is_string;
use function ltrim;
use function pathinfo;
use function token_get_all;

use const PATHINFO_EXTENSION;
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
    public static function build(Iterator|string $dir): array
    {
        if (is_string($dir)) {
            $dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        }

        $map = [];

        foreach ($dir as $file) {
            if (! $file->isFile()) {
                continue;
            }

            $path = $file->getRealPath() ?: $file->getPathname();

            if (pathinfo($path, PATHINFO_EXTENSION) !== 'php') {
                continue;
            }

            $classes = self::findClasses($path);

            foreach ($classes as $class) {
                $map[$class] = $path;
            }
        }

        return $map;
    }

    private static function findClasses(string $path): array
    {
        $contents = file_get_contents($path);
        $tokens = token_get_all($contents);

        $nsTokens = [T_STRING => true, T_NS_SEPARATOR => true];
        if (defined('T_NAME_QUALIFIED')) {
            $nsTokens[T_NAME_QUALIFIED] = true;
        }

        $classes = [];

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

                    // Find the classname
                    while (isset($tokens[++$i][1])) {
                        $t = $tokens[$i];
                        if ($t[0] === T_STRING) {
                            $class .= $t[1];
                        } elseif ($class !== '' && $t[0] === T_WHITESPACE) {
                            break;
                        }
                    }

                    $classes[] = ltrim($namespace . $class, '\\');
                    break;
                default:
                    break;
            }
        }

        return $classes;
    }
}
