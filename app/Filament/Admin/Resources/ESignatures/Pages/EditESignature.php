<?php

namespace App\Filament\Admin\Resources\ESignatures\Pages;

use App\Filament\Admin\Resources\ESignatures\ESignatureResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EditESignature extends EditRecord
{
    protected static string $resource = ESignatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (! empty($data['image_path']) && Storage::disk('public')->exists($data['image_path'])) {
            $mime = Storage::disk('public')->mimeType($data['image_path']) ?: 'image/png';
            $binary = Storage::disk('public')->get($data['image_path']);
            $data['signature_data'] = 'data:'.$mime.';base64,'.base64_encode($binary);
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $dataUrl = $data['signature_data'] ?? null;
        unset($data['signature_data']);

        if ($dataUrl === null) {
            $data['image_path'] = null;

            return $data;
        }

        if (preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $m)) {
            $ext = $m[1] === 'jpeg' ? 'jpg' : $m[1];
            $binary = base64_decode(substr($dataUrl, strpos($dataUrl, ',') + 1));
            $path = 'signatures/'.Str::uuid().'.'.$ext;
            Storage::disk('public')->put($path, $binary);
            $data['image_path'] = $path;
        }

        return $data;
    }
}
