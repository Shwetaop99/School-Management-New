<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use RuntimeException;
use thiagoalessio\TesseractOCR\TesseractOCR;

class IdCardImageAnalyzer
{
    /**
     * Analyze an ID-card design image.
     *
     * STEP 1:
     * - Detect image dimensions
     * - Run Tesseract OCR
     * - Return every detected OCR word
     * - Return exact bounding boxes
     *
     * IMPORTANT:
     * We intentionally DO NOT decide where dynamic student
     * fields should be placed yet.
     *
     * The admin will select/map those areas in the next step.
     */
    public function analyze(string $imagePath): array
    {
        if (!file_exists($imagePath)) {
            throw new RuntimeException(
                'ID-card image was not found.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Check image
        |--------------------------------------------------------------------------
        */

        $dimensions = getimagesize($imagePath);

        if (!$dimensions) {
            throw new RuntimeException(
                'Unable to determine ID-card image dimensions.'
            );
        }

        $imageWidth = (int) $dimensions[0];
        $imageHeight = (int) $dimensions[1];

        /*
        |--------------------------------------------------------------------------
        | Tesseract executable
        |--------------------------------------------------------------------------
        */

        $executable = config(
            'services.tesseract.executable',
            'C:\\Program Files\\Tesseract-OCR\\tesseract.exe'
        );

        if (!file_exists($executable)) {
            throw new RuntimeException(
                'Tesseract executable was not found at: ' .
                $executable
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Create Tesseract instance
        |--------------------------------------------------------------------------
        */

        $tesseract = new TesseractOCR($imagePath);

        $tesseract
            ->executable($executable)
            ->lang('eng')
            ->psm(6);

        /*
        |--------------------------------------------------------------------------
        | Get TSV output
        |--------------------------------------------------------------------------
        */

        try {
            $tsv = $tesseract
                ->tsv()
                ->run();
        } catch (\Throwable $e) {
            Log::error(
                'Tesseract OCR failed.',
                [
                    'image' => $imagePath,
                    'error' => $e->getMessage(),
                ]
            );

            throw new RuntimeException(
                'Tesseract OCR failed: ' .
                $e->getMessage()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Parse OCR words
        |--------------------------------------------------------------------------
        */

        $words = $this->parseTsv($tsv);

        /*
        |--------------------------------------------------------------------------
        | Return analysis
        |--------------------------------------------------------------------------
        */

        return [
            'image' => [
                'width' => $imageWidth,
                'height' => $imageHeight,
            ],

            /*
            |--------------------------------------------------------------------------
            | Every OCR word with its exact position
            |--------------------------------------------------------------------------
            */

            'words' => $words,

            /*
            |--------------------------------------------------------------------------
            | Fields are intentionally empty in STEP 1.
            |
            | In STEP 2 the admin will select OCR text and map it
            | to fields such as:
            |
            | name
            | roll_number
            | class
            | division
            | dob
            | etc.
            |--------------------------------------------------------------------------
            */

            'fields' => [],

            /*
            |--------------------------------------------------------------------------
            | Photo detection is intentionally disabled for now.
            |
            | We will allow the admin to define the photo area
            | manually in the template editor.
            |--------------------------------------------------------------------------
            */

            'photo' => null,

            'analysis_version' => 3,

            'analyzed_at' => now()->toDateTimeString(),
        ];
    }

    /**
     * Parse Tesseract TSV output.
     *
     * We only keep actual OCR words.
     *
     * Each word contains:
     *
     * text
     * left
     * top
     * width
     * height
     * confidence
     */
    private function parseTsv(string $tsv): array
    {
        $lines = preg_split(
            "/\r\n|\r|\n/",
            trim($tsv)
        );

        if (
            !$lines ||
            count($lines) < 2
        ) {
            return [];
        }

        /*
        |--------------------------------------------------------------------------
        | First line contains TSV column names
        |--------------------------------------------------------------------------
        */

        $headers = str_getcsv(
            array_shift($lines),
            "\t"
        );

        $result = [];

        foreach ($lines as $line) {

            if (trim($line) === '') {
                continue;
            }

            $columns = str_getcsv(
                $line,
                "\t"
            );

            /*
            |--------------------------------------------------------------------------
            | Ignore malformed rows
            |--------------------------------------------------------------------------
            */

            if (
                count($columns) !==
                count($headers)
            ) {
                continue;
            }

            $row = array_combine(
                $headers,
                $columns
            );

            if (!$row) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Level 5 = actual OCR word
            |--------------------------------------------------------------------------
            */

            $level = (int) (
                $row['level'] ?? 0
            );

            if ($level !== 5) {
                continue;
            }

            $text = trim(
                $row['text'] ?? ''
            );

            $confidence = (float) (
                $row['conf'] ?? -1
            );

            /*
            |--------------------------------------------------------------------------
            | Ignore empty OCR results
            |--------------------------------------------------------------------------
            */

            if (
                $text === '' ||
                $confidence < 0
            ) {
                continue;
            }

            $left = (int) (
                $row['left'] ?? 0
            );

            $top = (int) (
                $row['top'] ?? 0
            );

            $width = (int) (
                $row['width'] ?? 0
            );

            $height = (int) (
                $row['height'] ?? 0
            );

            /*
            |--------------------------------------------------------------------------
            | Store exact OCR information
            |--------------------------------------------------------------------------
            */

            $result[] = [
                'text' => $text,

                'left' => $left,

                'top' => $top,

                'width' => $width,

                'height' => $height,

                'right' => $left + $width,

                'bottom' => $top + $height,

                'confidence' => round(
                    $confidence,
                    2
                ),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Sort words top-to-bottom, left-to-right
        |--------------------------------------------------------------------------
        */

        usort(
            $result,
            function ($a, $b) {

                if ($a['top'] === $b['top']) {
                    return $a['left'] <=> $b['left'];
                }

                return $a['top'] <=> $b['top'];
            }
        );

        return $result;
    }
}