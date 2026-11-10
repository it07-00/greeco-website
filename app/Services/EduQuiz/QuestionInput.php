<?php

namespace App\Services\EduQuiz;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use ZipArchive;

class QuestionInput
{
    public const HEADERS = ['question', 'a', 'b', 'c', 'd', 'e', 'f', 'correct', 'seconds', 'explanation'];

    public function validate(array $input): array
    {
        $input['options'] = array_values(array_filter($input['options'] ?? [], fn ($option) => is_array($option) && (filled($option['text'] ?? null) || ! empty($option['correct']))));
        $validated = Validator::make($input, [
            'prompt' => ['required', 'string', 'max:2500'],
            'explanation' => ['nullable', 'string', 'max:3500'],
            'seconds' => ['required', 'integer', 'between:5,600'],
            'options' => ['required', 'array', 'min:2', 'max:6'],
            'options.*.text' => ['required', 'string', 'max:500'],
            'options.*.correct' => ['sometimes', 'boolean'],
        ], [
            'prompt.required' => 'Nhập nội dung câu hỏi.',
            'seconds.between' => 'Thời gian mỗi câu phải từ 5 đến 600 giây.',
            'options.min' => 'Mỗi câu cần ít nhất 2 đáp án.',
            'options.*.text.required' => 'Nhập nội dung cho đáp án được đánh dấu đúng.',
        ])->validate();

        $options = array_map(fn (array $option) => [
            'text' => trim($option['text']),
            'correct' => (bool) ($option['correct'] ?? false),
        ], $validated['options']);

        if (! array_filter(array_column($options, 'correct'))) {
            throw ValidationException::withMessages(['options' => 'Đánh dấu ít nhất một đáp án đúng.']);
        }

        unset($validated['options']);

        return [$validated, $options];
    }

    public function read(string $path, string $extension): array
    {
        if ($extension === 'xlsx') {
            $this->validateArchive($path);
        }

        $reader = IOFactory::createReader($extension === 'csv' ? 'Csv' : 'Xlsx');
        $reader->setReadDataOnly(true);
        $reader->setReadFilter(new class implements IReadFilter
        {
            public function readCell(string $columnAddress, int $row, string $worksheetName = ''): bool
            {
                return $row <= 501 && strlen($columnAddress) === 1 && $columnAddress <= 'J';
            }
        });

        try {
            $firstSheet = $reader->listWorksheetInfo($path)[0] ?? null;
            if (($firstSheet['totalRows'] ?? 0) > 501) {
                throw ValidationException::withMessages(['file' => 'Mỗi lần chỉ nhập tối đa 500 dòng dữ liệu.']);
            }
            if ($extension === 'xlsx') {
                $reader->setLoadSheetsOnly($firstSheet['worksheetName'] ?? []);
            }
            $spreadsheet = $reader->load($path);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Exception) {
            throw ValidationException::withMessages(['file' => 'Không đọc được tệp Excel/CSV. Hãy sử dụng tệp mẫu.']);
        }

        $sheet = $spreadsheet->getActiveSheet();
        $header = $sheet->rangeToArray('A1:J1', null, false, false)[0];
        if (array_map(fn ($cell) => strtolower(trim((string) $cell)), $header) !== self::HEADERS) {
            throw ValidationException::withMessages(['file' => 'Cột dữ liệu không khớp tệp mẫu.']);
        }

        $rows = [];
        for ($line = 2; $line <= $sheet->getHighestDataRow(); $line++) {
            $cells = $sheet->rangeToArray("A{$line}:J{$line}", null, false, false)[0];
            $cells = array_map(fn ($value) => trim((string) $value), $cells);
            if (! array_filter($cells, fn ($value) => $value !== '')) {
                continue;
            }
            $correct = preg_split('/[\s,;]+/', strtoupper($cells[7]), -1, PREG_SPLIT_NO_EMPTY);
            if (array_diff($correct, str_split('ABCDEF'))) {
                throw ValidationException::withMessages(['file' => "Dòng $line có ký hiệu đáp án đúng không hợp lệ."]);
            }
            $options = [];
            foreach (str_split('ABCDEF') as $position => $letter) {
                $options[] = ['text' => $cells[$position + 1], 'correct' => in_array($letter, $correct, true)];
            }

            try {
                $rows[] = $this->validate([
                    'prompt' => $cells[0],
                    'explanation' => $cells[9] ?: null,
                    'seconds' => $cells[8] ?: 30,
                    'options' => $options,
                ]);
            } catch (ValidationException $exception) {
                throw ValidationException::withMessages(['file' => "Dòng $line. ".$exception->validator->errors()->first()]);
            }
        }

        if (! $rows) {
            throw ValidationException::withMessages(['file' => 'Tệp chưa có câu hỏi.']);
        }

        return $rows;
    }

    private function validateArchive(string $path): void
    {
        $archive = new ZipArchive;
        if ($archive->open($path, ZipArchive::RDONLY) !== true) {
            throw ValidationException::withMessages(['file' => 'Tệp Excel không hợp lệ.']);
        }

        try {
            $bytes = 0;
            for ($index = 0; $index < $archive->numFiles; $index++) {
                $bytes += $archive->statIndex($index)['size'] ?? 0;
                if ($bytes > 24 * 1024 * 1024) {
                    throw ValidationException::withMessages(['file' => 'Dung lượng Excel sau giải nén vượt quá 24 MB.']);
                }
            }
        } finally {
            $archive->close();
        }
    }

    public function template(): Xlsx
    {
        $sheet = new Spreadsheet;
        $sheet->getActiveSheet()->fromArray([
            self::HEADERS,
            ['Nhập câu hỏi', 'Phương án 1', 'Phương án 2', '', '', '', '', 'B', 30, 'Nhập lời giải thích'],
        ]);

        return new Xlsx($sheet);
    }
}
