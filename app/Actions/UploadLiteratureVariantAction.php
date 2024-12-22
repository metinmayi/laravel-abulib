<?php

namespace App\Actions;

use App\Models\Literature;
use App\Models\LiteratureVariant;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Action for uploading literature variant
 */
class UploadLiteratureVariantAction
{
  /**
   * Constructor
   * @param array<mixed> $data Data passed in.
   */
    public function __construct(protected array $data)
    {
    }

  /**
   * Main method
   * @return array{bool, int}
   */
    public function handle(int $literatureId): array
    {
        if (! Literature::find($literatureId)) {
            Log::error('Tried to upload a literature variant without an existing literature', ['literature_id' => $literatureId, 'data' => $this->data]);
            return [false, -1];
        }

        $alreadyExists = LiteratureVariant::where('literature_id', $literatureId)
            ->where('language', $this->data['language'])
            ->exists();
        if ($alreadyExists) {
            Log::error('Tried to upload a literature variant with an existing language', ['literature_id' => $literatureId, 'data' => $this->data]);
            return [false, -1];
        }

        /** @var UploadedFile */
        $file = $this->data['file'];
        $fileName = Storage::disk('local')->putFile('', $file);

        $this->data['url'] = $fileName;
        $this->data['literature_id'] = $literatureId;

        $variant = new LiteratureVariant($this->data);
        return [$variant->save(), $variant->id];
    }
}
