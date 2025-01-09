<?php

namespace Tests\Feature;

use App\Models\Variant;
use Detection\MobileDetect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ReaderControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index method for mobile view.
     */
    #[DataProvider('renderDataProvider')]
    public function testRenderCorrectViewDependingOnMobileOrDesktop(bool $isMobile, string $expectedView): void
    {
        $mobileDetectMock = Mockery::mock(MobileDetect::class);

        /** @phpstan-ignore-next-line */
        $mobileDetectMock->shouldReceive('isMobile')
            ->once()
            ->andReturn($isMobile);

        // Bind the mock to the service container
        $this->app->instance(MobileDetect::class, $mobileDetectMock);

        // Call the index method
        $response = $this->get(route('read.index', ['variantId' => Variant::factory()->createOne()->id]));

        // Assert the correct view is returned
        $response->assertViewIs($expectedView);
    }

    /**
     * Render data provider
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function renderDataProvider(): array
    {
        return [
            'Mobile view' => [true, 'read.mobile'],
            'Desktop view' => [false, 'read.desktop'],
        ];
    }

    /**
     * Test that a 404 is returned if the literature variant is not found
     */
    public function testGetLiteratureBinary404IfNotFound(): void
    {
        $this->get(route('read.getLiteratureBinary', ['id' => -1]))
            ->assertStatus(404);
    }

    /**
     * Get literature binary
     */
    public function testGetLiteratureBinary(): void
    {
        $variant = Variant::factory()->createOne();

        $this->get(route('read.getLiteratureBinary', ['id' => $variant->id]))
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/pdf')
            ->assertContent(Storage::get($variant->url ?? '') ?? '');
    }

    /**
     * Test getting literature file successfully.
     */
    public function testGetLiteratureFileSuccess(): void
    {
        Storage::fake('local');
        $filePath = 'files/sample.pdf';
        Storage::put($filePath, 'PDF file content');

        $variant = Variant::factory()->create([
            'url' => $filePath,
        ]);

        $this->get(route('read.getLiteratureFile', ['id' => $variant->id]))
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/pdf')
            ->assertSee('PDF file content', false);
    }

    /**
     * Test getting literature file for a variant without a file URL.
     */
    public function test_get_literature_file_not_found_due_to_missing_url(): void
    {
        $variant = Variant::factory()->create([
            'url' => null,
        ]);

        $this->get(route('read.getLiteratureFile', ['id' => $variant->id]))
            ->assertStatus(404);
    }

    /**
     * Test getting literature file for a non-existent variant.
     */
    public function test_get_literature_file_not_found_due_to_missing_variant(): void
    {
        $this->get(route('read.getLiteratureFile', ['id' => 9999]))
            ->assertStatus(404);
    }
}
