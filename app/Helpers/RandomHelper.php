<?php

namespace App\Helpers;

class RandomHelper
{
	public static function generateOTP($length = 6)
	{
		$otp = '';
		for ($i = 0; $i < $length; $i++) {
			$otp .= rand(0, 9);
		}
		return $otp;
	}
}
