<?php
declare(strict_types=1);

namespace App\Components\Attachments;

use App\Components\Attachments\AttachmentInterface;

abstract class AttachmentFactory
{
    public static function create(string $type): AttachmentInterface
    {
        $className = 'App\\Components\\Attachments\\' . ucfirst($type) . 'Attachment';
        if (class_exists($className)) {
            $reflection = new \ReflectionClass($className);
            return $reflection->newInstance();
        }
        throw new \Exception("Class $className not found.");
    }
}
