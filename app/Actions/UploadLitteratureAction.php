<?php

namespace App\Actions;

use App\Models\Litterature;
use Illuminate\Support\Facades\DB;

/**
 * Action for uploading Litterature
 */
class UploadLitteratureAction
{

  /**
   * Constructor.
   */
  public function __construct(protected array $data, protected UploadLitteratureVariantAction $uploadLitteratureVariantAction) {}

  /**
   * Main method
   */
  public function handle(): void
  {
    DB::transaction(function () {
      $litterature = new Litterature(['category' => $this->data['category']]);
      $litterature->save();

      $this->uploadLitteratureVariantAction->handle($litterature->id);
    });
  }
}
