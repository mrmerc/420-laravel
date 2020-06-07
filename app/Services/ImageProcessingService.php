<?php
declare(strict_types=1);

namespace App\Services;

use Intervention\Image\Facades\Image;
use Widmogrod\Monad\Either\{Either, Left, Right};

final class ImageProcessingService
{
    const IMG_BASE64_REGEXP = '/^\s*data:([a-z]+\/[a-z0-9\-\+]+(;[a-z\-]+\=[a-z0-9\-]+)?)?(;base64)?,[a-z0-9\!\$\&\'\,\(\)\*\+\,\;\=\-\.\_\~\:\@\/\?\%\s]*\s*$/i';

    /**
     * Save image on server
     *
     * @param string $urlData
     *
     * @return Either
     */
    public function saveImg(string $urlData): Either
    {
		try {
            if (!preg_match(self::IMG_BASE64_REGEXP, $urlData)) {
                throw new \Exception('Argument is not a valid base64 image');
            }

            if (strlen($urlData) <= 128) {
                throw new \Exception('URI Data length is less than minimum');
            }

            list($mime, $data)  = explode(';', $urlData);
            list(, $data)       = explode(',', $data);

            $ext = explode('/', $mime)[1];

            $fileName = mt_rand() . time() . '.' . $ext;
            $path = storage_path().'/app/public/uploads/images/' . $fileName;

            Image::make($urlData)->save($path);

            $link = url('storage/uploads/images/' . $fileName);

            return Right::of($link);
		}
		catch (\Throwable $e) {
			return Left::of($e);
		}
	}
}
