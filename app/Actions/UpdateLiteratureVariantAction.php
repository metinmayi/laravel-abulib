<?php

namespace App\Actions;

use App\Models\LiteratureVariant;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Action for updating Literature
 */
class UpdateLiteratureVariantAction
{
  /**
   * Constructor.
   * @param integer              $id   The id of the variant to update.
   * @param array<string, mixed> $data The data to update.
   */
    public function __construct(protected int $id, protected array $data)
    {
    }

  /**
   * Main method
   */
    public function handle(): bool
    {
        $variant = LiteratureVariant::query()->find($this->id);
        if (! $variant) {
            Log::error("Variant with id $this->id not found when attempting to update");
            return false;
        }

        if (isset($this->data['title']) && is_string($this->data['title'])) {
            $variant->title = $this->data['title'];
        }

        if (isset($this->data['language']) && is_string($this->data['language'])) {
            if (! $this->updateLanguage($this->data['language'], $variant)) {
                return false;
            }
        }

        if (isset($this->data['description']) && is_string($this->data['description'])) {
            $variant->description = $this->data['description'];
        }

        if (isset($this->data['file']) && $this->data['file'] instanceof UploadedFile) {
            if (! $this->updateFile($this->data['file'], $variant)) {
                return false;
            }
        }

        $variant->save();

        return true;
    }

    /**
     * Update the file of the variant.
     */
    protected function updateFile(UploadedFile $file, LiteratureVariant &$variant): bool
    {
        $url = Storage::putFile('', $file);
        if (! $url) {
            Log::error("Failed to add new file at $variant->url");
            return false;
        }

        if (! Storage::delete($variant->url)) {
            Log::error("Failed to delete old file at $variant->url");
            return false;
        }

        $variant->url = $url;
        return true;
    }

    /**
     * Update the language of the variant.
     */
    protected function updateLanguage(string $language, LiteratureVariant &$variant): bool
    {
        $alreadyExist = LiteratureVariant::query()
            ->where('language', $language)
            ->where('literature_id', $variant->literature_id)
            ->first();
        if ($alreadyExist) {
            Log::error("Language $language already exists for literature with id $variant->literature_id");
            return false;
        }

        $variant->language = $language;
        return true;
    }
}
