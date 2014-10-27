<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Helpers;

class Plural extends AbstractHelper {

	/**
	 * Склонение числительных (для русского языка)
	 *
	 * Принимает число и выбирает соответствующее склонение числительного. Всего 3 варианта, которые
	 * соответствуют числам 1, 2 и 5.
	 *
	 * @param int $number
	 * @param string $one
	 * @param string $two
	 * @param string $five
	 * @return string
	 */
	function execute($number, $one, $two, $five) {
		if (($number - $number % 10) % 100 != 10) {
			if ($number % 10 == 1) {
				$result = $one;
			} elseif ($number % 10 >= 2 && $number % 10 <= 4) {
				$result = $two;
			} else {
				$result = $five;
			}
		} else {
			$result = $five;
		}
		return $result;
	}
}
