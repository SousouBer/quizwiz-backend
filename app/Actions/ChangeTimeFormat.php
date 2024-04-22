<?php

namespace App\Actions;

class ChangeTimeFormat
{
	public static function handle(string $time): string
	{
		$timeInMinutes = 0;

		list($hours, $minutes) = explode(':', $time);

		$hours = intval($hours);
		$minutes = intval($minutes);

		if ($minutes >= 30) {
			$timeInMinutes = $hours + 1;
		} else {
			$timeInMinutes = $hours;
		}

		return $timeInMinutes;
	}
}
