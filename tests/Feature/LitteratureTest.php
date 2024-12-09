<?php

namespace Tests\Feature;

use App\Models\Litterature;
use App\Models\LitteratureVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LitteratureTest extends TestCase
{
    use RefreshDatabase;

    protected const DISK_STORE = 'litteratures';
    protected string $litteratureCategory = 'research';

    protected string $variantTitle = 'Test Title';
    protected string $variantDescription = 'Test Description';
    protected string $variantLanguage = 'Test Language';
    

    public function test_upload_litterature_creates_litterature(): void
    {
        $this->uploadLitterature();
    }

    public function test_upload_litterature_creates_litterature_variant(): void
    {
        $this->assertCount(0, LitteratureVariant::all());
        $litterature = $this->uploadLitterature();

        $variants = LitteratureVariant::all();
        $this->assertCount(1, $variants);

        $variant = $variants->first();
        $this->assertNotNull($variant);
        $this->assertEquals($this->variantTitle, $variant->title);
        $this->assertEquals($this->variantDescription, $variant->description);
        $this->assertEquals($this->variantLanguage, $variant->language);
        $this->assertEquals($this->getExpectedFileName(), $variant->url);
        $this->assertEquals($litterature->id, $variant->litterature_id);
        $this->assertTrue(Storage::disk(self::DISK_STORE)->exists($this->getExpectedFileName()));
    }

    protected function uploadLitterature(): Litterature
    {
        $this->assertCount(0, Litterature::all());

        Storage::fake(self::DISK_STORE);
        $file = UploadedFile::fake()->create('test.pdf', 100);
        $postData = [
            'title' => $this->variantTitle,
            'description' => $this->variantDescription,
            'language' => $this->variantLanguage,
            'category' => $this->litteratureCategory,
            'file' => $file,
        ];

        $response = $this->post('/litterature', $postData);
        $response->assertStatus(201);

        $litteratures = Litterature::all();
        $this->assertCount(1, $litteratures);
        $this->assertInstanceOf(Litterature::class, $litteratures->first());
        $this->assertEquals($this->litteratureCategory, $litteratures->first()->category);
        return $litteratures->first();
    }

    protected function getExpectedFileName(): string
    {
        return "{$this->variantTitle}-{$this->variantLanguage}.pdf";
    }
}
