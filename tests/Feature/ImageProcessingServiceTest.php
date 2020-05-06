<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\ImageProcessingService;
use Widmogrod\Monad\Either\{Right};
use Illuminate\Support\Facades\File;

class ImageProcessingServiceTest extends TestCase
{
    /**
     * @var ImageProcessingService
     */
    private $service;

    protected function setUp(): void {
        parent::setUp();

        $this->service = new ImageProcessingService();
    }

    protected function tearDown(): void {
        $images = File::files(storage_path('app/public/uploads/images'));
        File::delete($images);

        parent::tearDown();
    }

    public function testImageSave()
    {
        $urlData = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAHElEQVQI12P4//8/w38GIAXDIBKE0DHxgljNBAAO9TXL0Y4OHwAAAABJRU5ErkJggg==';

        $result = $this->service->saveImg($urlData);

        $this->assertInstanceOf(Right::class, $result);

        $appUrl = url('/');

        $this->assertStringContainsString($appUrl, $result->extract(), 'Image link contains APP_URL');
    }
}
