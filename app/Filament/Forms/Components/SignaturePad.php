<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

/**
 * Field TTD: gambar langsung di kanvas ATAU upload file gambar.
 * State-nya string base64 data-URI PNG (bukan kolom DB langsung — konversi ke
 * file di storage dilakukan di halaman Create/Edit lewat mutateFormDataBefore*).
 */
class SignaturePad extends Field
{
    protected string $view = 'filament.forms.components.signature-pad';
}
