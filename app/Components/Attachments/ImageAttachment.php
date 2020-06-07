<?php
declare(strict_types=1);

namespace App\Components\Attachments;

use App\Services\ImageProcessingService;
use App\Components\Attachments\AttachmentInterface;
use Widmogrod\Monad\Either\{Left, Right, Either};

final class ImageAttachment implements AttachmentInterface
{
    /**
     * @var ImageProcessingService $imageProcessingService
     */
    private $imageProcessingService;

    public function __construct()
    {
        $this->imageProcessingService = new ImageProcessingService;
    }

    public function processAttachment(array $attachmentData): Either
    {
        if (!array_key_exists('source', $attachmentData)) {
            return Left::of(new \Exception('Attachment SOURCE is NULL'));
        }

        $imgUri = $this->imageProcessingService->saveImg($attachmentData['source']);

        if ($imgUri instanceof Left) {
            return $imgUri;
        }
        return Right::of($imgUri->extract());
    }
}
