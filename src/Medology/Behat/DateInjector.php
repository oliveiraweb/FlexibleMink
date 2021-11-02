<?php

namespace Medology\Behat;

use Behat\Behat\Context\Context;
use Carbon\Carbon;
use InvalidArgumentException;

class DateInjector implements Context
{
    public const REGEX = "/\(a date\/time of '([^']+)'( formatted as '([^']+)')?\)/";

    protected static $format_map = [
        'a Atom date and time'                          => 'Y-m-d\TH:i:sP',
        'a MySQL date'                                  => 'Y-m-d',
        'a MySQL date and time'                         => 'Y-m-d H:i:s',
        'a US date'                                     => 'm/d/Y',
        'a US date and time'                            => 'm/d/Y H:i:s',
        'a US time'                                     => 'h:i',
        'a US time with uppercase meridiem'             => 'g:i A',
        'a US time with meridiem'                       => 'g:i a',
        'a US military time'                            => 'H:i',
        'a US time with seconds and uppercase meridiem' => 'g:i:s A',
        'a US time with seconds and meridiem'           => 'g:i:s a',
        'a US military time with seconds'               => 'H:i:s',
        'a US date and 12hr time'                       => 'm/d/Y \a\t g:i A',
    ];

    /**
     * Searches a string for date/time placeholders and uses carbon to replace them.
     *
     * @Transform /^(.*\(a date\/time of '([^']+)'( formatted as '([^']+)')?\).*)$/
     *
     * @param string $subject the string to manipulate
     *
     * @return string the subject with the date/time placeholders replaced
     */
    public function injectCarbonStrings(string $subject): string
    {
        preg_match_all(self::REGEX, $subject, $matches);

        foreach ($matches[0] as $i => $match) {
            $subject = $this->injectCarbonString($match, $matches[1][$i], $matches[3][$i], $subject);
        }

        return $subject;
    }

    /**
     * Replaces a single instance of a specified string with a parsed and optionally formatted Carbon date/time.
     *
     * @param string      $search       the value from the string to replace
     * @param string      $carbonString the value to pass to Carbon::parse to generate the replacement string
     * @param string|null $format       the optional format to use when generating the replacement. See Carbon::format().
     * @param string      $subject      the string to replace the value within
     *
     * @return string the subject with the search string replaced
     */
    public function injectCarbonString(string $search, string $carbonString, ?string $format, string $subject): string
    {
        $date = Carbon::parse($carbonString);

        return str_replace($search, $format ? $date->format($this->resolveFormat($format)) : $date, $subject);
    }

    /**
     * Returns a defensive copy of the format_map property.
     *
     * @return string[]
     */
    public function getFormatMap(): array
    {
        return self::$format_map;
    }

    /**
     * Resolves a human-readable format to a Carbon format via self::$format_map.
     *
     * @param  string $format the human-readable format to resolve.
     * @throws InvalidArgumentException if $format is not a valid key from self::$format_map.
     * @return string The resolved Carbon format.
     */
    protected function resolveFormat(string $format): string {
        if (!isset(self::$format_map[$format])) {
            throw new InvalidArgumentException(
                "'$format' is not a valid format. Please use one of: '" . join("', '", self::$format_map) . "'"
            );
        }

        return self::$format_map[$format];
    }
}
