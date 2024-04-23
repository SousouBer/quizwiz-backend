<?php

namespace App\Actions;

class ChangeTimeFormat
{
	public static function handle(string $time): string
	{
		$timeInMinutes = 0;

		list($minutes, $seconds) = explode(':', $time);

		$minutes = intval($minutes);
		$seconds = intval($seconds);

		if ($seconds >= 30) {
			$timeInMinutes = $minutes + 1;
		} else {
			$timeInMinutes = $minutes;
		}

		return $timeInMinutes;
	}
}
