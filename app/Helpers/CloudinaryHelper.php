<?php

namespace App\Helpers;

use Cloudinary\Cloudinary;

class CloudinaryHelper
{
	protected static function cloudinary()
	{
		return new Cloudinary([
			'cloud' => [
				'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
				'api_key'    => env('CLOUDINARY_API_KEY'),
				'api_secret' => env('CLOUDINARY_API_SECRET'),
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
