<?php

namespace App\Services;

use App\Models\Student;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class StudentPhotoService
{
    /**
     * Upload student photo to Cloudinary.
     */
    public function upload(
        UploadedFile $photo,
        Student $student
    ): array {
        $folder = 'school-management/students/' .
            $student->student_id;

        $publicId =
            'student-photo-' .
            Str::lower(Str::random(12));

        $cloudinary = Cloudinary::getFacadeRoot();

        $upload = method_exists($cloudinary, 'uploadApi')
            ? $cloudinary->uploadApi()->upload(
                $photo->getRealPath(),
                [
                    'folder' => $folder,
                    'public_id' => $publicId,
                    'resource_type' => 'image',
                    'overwrite' => false,
                ]
            )
            : Cloudinary::upload(
                $photo->getRealPath(),
                [
                    'folder' => $folder,
                    'public_id' => $publicId,
                    'resource_type' => 'image',
                    'overwrite' => false,
                ]
            );

        $secureUrl = null;
        if (isset($upload['secure_url'])) {
            $secureUrl = $upload['secure_url'];
        } elseif (is_object($upload) && method_exists($upload, 'getSecurePath')) {
            $secureUrl = $upload->getSecurePath();
        }

        if (empty($secureUrl)) {
            throw new \RuntimeException(
                'Cloudinary did not return a secure URL.'
            );
        }

        $resolvedPublicId = isset($upload['public_id'])
            ? $upload['public_id']
            : (is_object($upload) && method_exists($upload, 'getPublicId') ? $upload->getPublicId() : $publicId);

        return [
            'public_id' => $resolvedPublicId,
            'url' => $secureUrl,
        ];
    }

    /**
     * Delete existing Cloudinary photo.
     */
    public function delete(?string $publicId): void
    {
        if (empty($publicId)) {
            return;
        }

        try {
            $cloudinary = Cloudinary::getFacadeRoot();
            if (method_exists($cloudinary, 'uploadApi')) {
                $cloudinary->uploadApi()->destroy($publicId);
            } else {
                Cloudinary::destroy($publicId);
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }
}