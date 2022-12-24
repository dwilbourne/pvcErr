<?php

/**
 * @author: Doug Wilbourne (dougwilbourne@gmail.com)
 */

declare(strict_types=1);

namespace pvc\err\stock;

/**
 *
 * Out of Bounds exceptions should be thrown when an illegal index was requested. This represents errors that
 * cannot be detected at compile time.
 *
 * The PHP library does not use this exception itself, so this exception can only be generated by throwing
 * it explicitly in your code.
 *
 * Example:
 *
 *        $month_names = new splFixedArray(12);
 *        $month_names[0] = "Jan";
 *        $month_names[1] = "Feb";
 *        $month_names[2] = "Mar";
 *        $month_names[3] = "Apr";
 *        $month_names[4] = "May";
 *        $month_names[5] = "Jun";
 *        $month_names[6] = "Jul";
 *        $month_names[7] = "Aug";
 *        $month_names[8] = "Sep";
 *        $month_names[9] = "Oct";
 *        $month_names[10] = "Nov";
 *        $month_names[11] = "Dec";
 *
 *        function getMonthName(int $n) {
 *            if ($n < 1 || $n > 12) {
 *                throw new OutOfRangeException("illegal month number supplied.");
 *            }
 *            else {
 *                return $month_names[$n - 1];
 *            }
 *        }
 *
 *        function getJan() {
 *            return getMonthName(0);
 *        }
 *
 *
 */
class OutOfBoundsException extends RuntimeException
{
}