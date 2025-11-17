<?php

namespace App\Helpers;

use Cloudinary\Cloudinary;

class CloudinaryHelper
{
	protected static function cloudinary()
	{
		return new Cloudinary([
			'cloud' => [
				'cloud_name' => config('cloudinary.cloud_name'),
				'api_key'    => config('cloudinary.api_key'),
				'api_secret' => config('cloudinary.api_secret'),
			],
			'url' => [
				'secure' => true
			]
		]);
	}

	/**
	 * Upload file lên Cloudinary
	 * @param  \Illuminate\Http\UploadedFile|string $file
	 * @param  string $folder
	 * @return string URL ảnh
	 */
	public static function upload($file, $folder = 'default')
	{
		$uploadedFile = self::cloudinary()->uploadApi()->upload(
			is_string($file) ? $file : $file->getRealPath(),
			['folder' => $folder]
		);

		return $uploadedFile['secure_url'] ?? null;
	}
}
