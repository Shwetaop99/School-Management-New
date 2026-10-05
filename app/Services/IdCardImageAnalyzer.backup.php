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
     * Returns:
     * - image dimensions
     * - OCR words
     * - automatically detected fields
     * - possible photo area
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
        |
        | Tesseract TSV contains:
        |
        | level
        | page_num
        | block_num
        | par_num
        | line_num
        | word_num
        | left
        | top
        | width
        | height
        | conf
        | text
        |
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
        | Parse TSV
        |--------------------------------------------------------------------------
        */

        $words = $this->parseTsv($tsv);

        /*
        |--------------------------------------------------------------------------
        | Detect fields
        |--------------------------------------------------------------------------
        */

        $detectedFields = $this->detectFields(
            $words,
            $imageWidth,
            $imageHeight
        );

        /*
        |--------------------------------------------------------------------------
        | Detect photo area
        |--------------------------------------------------------------------------
        */

        $photoArea = $this->detectPhotoArea(
            $imagePath,
            $words,
            $imageWidth,
            $imageHeight
        );

        /*
        |--------------------------------------------------------------------------
        | Return complete analysis
        |--------------------------------------------------------------------------
        */

        return [
            'image' => [
                'width' => $imageWidth,
                'height' => $imageHeight,
            ],

            'words' => $words,

            'fields' => $detectedFields,

            'photo' => $photoArea,

            'analysis_version' => 1,

            'analyzed_at' =>
                now()->toDateTimeString(),
        ];
    }

    /**
     * Parse Tesseract TSV output.
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
        | First line contains column names
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
            | Only process actual OCR words
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
            | Ignore empty / invalid OCR results
            |--------------------------------------------------------------------------
            */

            if (
                $text === '' ||
                $confidence < 0
            ) {
                continue;
            }

            $result[] = [
                'text' => $text,

                'left' => (int) (
                    $row['left'] ?? 0
                ),

                'top' => (int) (
                    $row['top'] ?? 0
                ),

                'width' => (int) (
                    $row['width'] ?? 0
                ),

                'height' => (int) (
                    $row['height'] ?? 0
                ),

                'confidence' =>
                    round(
                        $confidence,
                        2
                    ),
            ];
        }

        return $result;
    }

    /**
     * Detect common ID-card labels.
     */
    private function detectFields(
        array $words,
        int $imageWidth,
        int $imageHeight
    ): array {
        $fields = [];

        /*
        |--------------------------------------------------------------------------
        | Supported labels
        |--------------------------------------------------------------------------
        */

        $labelMap = [

            'name' => [
                'name',
                'student name',
                'full name',
                'studentname',
            ],

            'student_id' => [
                'student id',
                'student no',
                'student number',
                'id no',
                'id number',
                'studentid',
            ],

            'class' => [
                'class',
                'standard',
                'std',
            ],

            'section' => [
                'section',
                'div',
                'division',
            ],

            'roll_number' => [
                'roll no',
                'roll number',
                'roll',
                'rollno',
            ],

            'gr_no' => [
                'gr no',
                'gr number',
                'gr no.',
                'register no',
                'register number',
                'registration no',
            ],

            'dob' => [
                'dob',
                'date of birth',
                'birth date',
                'birthdate',
            ],

            'blood_group' => [
                'blood group',
                'blood',
            ],

            'phone' => [
                'phone',
                'mobile',
                'contact',
                'mobile no',
                'phone no',
            ],

            'father_name' => [
                'father name',
                "father's name",
                'father',
            ],

            'mother_name' => [
                'mother name',
                "mother's name",
                'mother',
            ],

            'address' => [
                'address',
            ],

            'academic_year' => [
                'academic year',
                'academic',
                'year',
            ],

            'gender' => [
                'gender',
                'sex',
            ],

            'blood' => [
                'blood',
            ],

            'caste' => [
                'caste',
            ],

            'religion' => [
                'religion',
            ],

            'nationality' => [
                'nationality',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Scan OCR words
        |--------------------------------------------------------------------------
        */

        foreach (
            $words as $index => $word
        ) {

            $current =
                $this->normalizeText(
                    $word['text']
                );

            if ($current === '') {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Single-word labels
            |--------------------------------------------------------------------------
            */

            foreach (
                $labelMap as $field => $labels
            ) {

                foreach (
                    $labels as $label
                ) {

                    if (
                        $this->matchesLabel(
                            $current,
                            $label
                        )
                    ) {

                        /*
                        |--------------------------------------------------------------------------
                        | Don't overwrite a higher-confidence
                        | detection with a lower-confidence one.
                        |--------------------------------------------------------------------------
                        */

                        if (
                            !isset(
                                $fields[$field]
                            ) ||
                            (
                                $word['confidence'] >
                                $fields[$field]['confidence']
                            )
                        ) {

                            $fields[$field] =
                                $this->makeFieldPosition(
                                    $field,
                                    $word,
                                    $imageWidth,
                                    $imageHeight
                                );
                        }
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Two-word labels
            |--------------------------------------------------------------------------
            */

            if (
                isset(
                    $words[$index + 1]
                )
            ) {

                $next =
                    $words[$index + 1];

                /*
                |--------------------------------------------------------------------------
                | Make sure the next word is
                | actually close to the current word.
                |--------------------------------------------------------------------------
                */

                $currentRight =
                    $word['left'] +
                    $word['width'];

                $gap =
                    $next['left'] -
                    $currentRight;

                $sameLine =
                    abs(
                        $next['top'] -
                        $word['top']
                    ) <=
                    max(
                        10,
                        $word['height']
                    );

                $closeEnough =
                    $gap >= -5 &&
                    $gap <= 100;

                if (
                    !$sameLine ||
                    !$closeEnough
                ) {
                    continue;
                }

                $nextText =
                    $this->normalizeText(
                        $next['text']
                    );

                $combined =
                    $current .
                    ' ' .
                    $nextText;

                foreach (
                    $labelMap as $field => $labels
                ) {

                    foreach (
                        $labels as $label
                    ) {

                        if (
                            $this->matchesLabel(
                                $combined,
                                $label
                            )
                        ) {

                            $combinedWord = [

                                'text' =>
                                    $word['text'] .
                                    ' ' .
                                    $next['text'],

                                'left' =>
                                    min(
                                        $word['left'],
                                        $next['left']
                                    ),

                                'top' =>
                                    min(
                                        $word['top'],
                                        $next['top']
                                    ),

                                'width' =>
                                    max(
                                        $word['left'] +
                                            $word['width'],

                                        $next['left'] +
                                            $next['width']
                                    )
                                    -
                                    min(
                                        $word['left'],
                                        $next['left']
                                    ),

                                'height' =>
                                    max(
                                        $word['height'],
                                        $next['height']
                                    ),

                                'confidence' =>
                                    min(
                                        $word['confidence'],
                                        $next['confidence']
                                    ),
                            ];

                            if (
                                !isset(
                                    $fields[$field]
                                ) ||
                                (
                                    $combinedWord[
                                        'confidence'
                                    ] >
                                    $fields[$field][
                                        'confidence'
                                    ]
                                )
                            ) {

                                $fields[$field] =
                                    $this->makeFieldPosition(
                                        $field,
                                        $combinedWord,
                                        $imageWidth,
                                        $imageHeight
                                    );
                            }
                        }
                    }
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Return indexed array
        |--------------------------------------------------------------------------
        */

        return array_values(
            $fields
        );
    }

    /**
     * Create automatic field coordinates.
     */
    private function makeFieldPosition(
        string $field,
        array $word,
        int $imageWidth,
        int $imageHeight
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Label coordinates
        |--------------------------------------------------------------------------
        */

        $labelRight =
            $word['left'] +
            $word['width'];

        /*
        |--------------------------------------------------------------------------
        | Automatically put value
        | after the label.
        |--------------------------------------------------------------------------
        */

        $valueX =
            $labelRight + 8;

        $valueY =
            $word['top'];

        /*
        |--------------------------------------------------------------------------
        | Keep value inside image
        |--------------------------------------------------------------------------
        */

        $valueX =
            min(
                $valueX,
                $imageWidth - 20
            );

        /*
        |--------------------------------------------------------------------------
        | Available width
        |--------------------------------------------------------------------------
        */

        $valueWidth =
            max(
                50,
                $imageWidth -
                $valueX -
                10
            );

        /*
        |--------------------------------------------------------------------------
        | Font size based on detected label
        |--------------------------------------------------------------------------
        */

        $fontSize =
            max(
                10,
                min(
                    22,
                    $word['height']
                )
            );

        /*
        |--------------------------------------------------------------------------
        | Return normalized coordinates
        |--------------------------------------------------------------------------
        */

        return [

            'key' =>
                $field,

            'label' =>
                $word['text'],

            'label_x' =>
                $word['left'],

            'label_y' =>
                $word['top'],

            'label_width' =>
                $word['width'],

            'label_height' =>
                $word['height'],

            'x' =>
                round(
                    (
                        $valueX /
                        $imageWidth
                    ) * 100,
                    4
                ),

            'y' =>
                round(
                    (
                        $valueY /
                        $imageHeight
                    ) * 100,
                    4
                ),

            'width' =>
                round(
                    (
                        $valueWidth /
                        $imageWidth
                    ) * 100,
                    4
                ),

            'height' =>
                round(
                    (
                        max(
                            25,
                            $word['height'] + 8
                        ) /
                        $imageHeight
                    ) * 100,
                    4
                ),

            'font_size' =>
                $fontSize,

            'font_weight' =>
                'normal',

            'text_align' =>
                'left',

            'confidence' =>
                $word['confidence'],
        ];
    }

    /**
     * Detect a possible photo region.
     *
     * This is currently a heuristic.
     */
    private function detectPhotoArea(
        string $imagePath,
        array $words,
        int $imageWidth,
        int $imageHeight
    ): ?array {

        /*
        |--------------------------------------------------------------------------
        | If there is no OCR text,
        | don't guess aggressively.
        |--------------------------------------------------------------------------
        */

        if (empty($words)) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Candidate photo dimensions.
        |--------------------------------------------------------------------------
        */

        $candidateWidth =
            (int) round(
                $imageWidth * 0.35
            );

        $candidateHeight =
            (int) round(
                $imageHeight * 0.30
            );

        /*
        |--------------------------------------------------------------------------
        | Center candidate.
        |--------------------------------------------------------------------------
        */

        $candidateX =
            (int) round(
                (
                    $imageWidth -
                    $candidateWidth
                ) / 2
            );

        $candidateY =
            (int) round(
                $imageHeight * 0.20
            );

        /*
        |--------------------------------------------------------------------------
        | Keep inside image.
        |--------------------------------------------------------------------------
        */

        $candidateX =
            max(
                0,
                min(
                    $candidateX,
                    $imageWidth -
                    $candidateWidth
                )
            );

        $candidateY =
            max(
                0,
                min(
                    $candidateY,
                    $imageHeight -
                    $candidateHeight
                )
            );

        return [

            'key' =>
                'profile_image',

            'x' =>
                round(
                    (
                        $candidateX /
                        $imageWidth
                    ) * 100,
                    4
                ),

            'y' =>
                round(
                    (
                        $candidateY /
                        $imageHeight
                    ) * 100,
                    4
                ),

            'width' =>
                round(
                    (
                        $candidateWidth /
                        $imageWidth
                    ) * 100,
                    4
                ),

            'height' =>
                round(
                    (
                        $candidateHeight /
                        $imageHeight
                    ) * 100,
                    4
                ),

            'confidence' =>
                0,

            'detection_method' =>
                'photo_region_heuristic',
        ];
    }

    /**
     * Normalize OCR text.
     */
    private function normalizeText(
        string $text
    ): string {

        $text =
            strtolower(
                trim($text)
            );

        /*
        |--------------------------------------------------------------------------
        | Replace punctuation with spaces.
        |--------------------------------------------------------------------------
        */

        $text =
            preg_replace(
                '/[^a-z0-9\s]/',
                ' ',
                $text
            );

        /*
        |--------------------------------------------------------------------------
        | Remove duplicate spaces.
        |--------------------------------------------------------------------------
        */

        $text =
            preg_replace(
                '/\s+/',
                ' ',
                $text
            );

        return trim($text);
    }

    /**
     * Compare OCR text with known label.
     */
    private function matchesLabel(
        string $detected,
        string $label
    ): bool {

        $detected =
            $this->normalizeText(
                $detected
            );

        $label =
            $this->normalizeText(
                $label
            );

        return $detected === $label;
    }
}