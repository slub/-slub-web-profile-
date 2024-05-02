<?php

declare(strict_types=1);

/*
 * This file is part of the package slub/slub-web-profile
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Slub\SlubWebProfile\Utility;

final class DotUtility
{
    /**
     * @param array $settings
     * @return array
     */
    public static function remove(array $settings = []): array
    {
        $conf = [];

        foreach ($settings as $key => $value) {
            $conf[self::removeAtTheEnd($key)] = is_array($value)
                ? self::remove($value)
                : $value;
        }

        return $conf;
    }

    /**
     * @param $string
     * @return string
     */
    private static function removeAtTheEnd($string): string
    {
        return (string)preg_replace('/\.$/', '', $string);
    }
}
