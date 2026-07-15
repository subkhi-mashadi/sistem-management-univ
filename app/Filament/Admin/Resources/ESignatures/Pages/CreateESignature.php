<?php

namespace App\Filament\Admin\Resources\ESignatures\Pages;

use App\Filament\Admin\Resources\ESignatures\ESignatureResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateESignature extends CreateRecord
{
    protected static string $resource = ESignatureResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $dataUrl = $data['signature_data'] ?? null;
        unset($data['signature_data']);

        if ($dataUrl && preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $m)) {
            $ext = $m[1] === 'jpeg' ? 'jpg' : $m[1];
            $binary = base64_decode(substr($dataUrl, strpos($dataUrl, ',') + 1));
            $path = 'signatures/'.Str::uuid().'.'.$ext;
            Storage::disk('public')->put($path, $binary);
            $data['image_path'] = $path;
        }

        return $data;
    }
}
