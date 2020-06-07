<?php
declare(strict_types=1);

namespace App\Components\Attachments;

use Widmogrod\Monad\Either\Either;

interface AttachmentInterface
{
    public function processAttachment(array $attachmentData): Either;
}
