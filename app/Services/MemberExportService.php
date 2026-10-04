<?php

namespace App\Services;

use App\Models\Member;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MemberExportService
{
    private const string SHEET_TITLE = 'Members';

    private const string CONTENT_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

    /** Column header => value resolver for each exported member row. */
    private static function columns(): array
    {
        return [
            'ID'                      => fn (Member $m) => $m->id,
            'First Name'              => fn (Member $m) => $m->first_name,
            'Last Name'               => fn (Member $m) => $m->last_name,
            'Sex'                     => fn (Member $m) => $m->sex?->name,
            'Marital Status'          => fn (Member $m) => $m->maritalStatus?->name,
            'Date of Birth'           => fn (Member $m) => $m->date_birthday?->toDateString(),
            'Age'                     => fn (Member $m) => $m->age,
            'National ID'             => fn (Member $m) => $m->national_id,
            'Email'                   => fn (Member $m) => $m->email,
            'Mobile'                  => fn (Member $m) => $m->mobile_tel,
            'Employed'                => fn (Member $m) => $m->employed ? 'Yes' : 'No',
            'Is Member'               => fn (Member $m) => $m->is_member ? 'Yes' : 'No',
            'Attends Sunday School'   => fn (Member $m) => $m->attends_sunday_school ? 'Yes' : 'No',
            "Father's Name"           => fn (Member $m) => $m->fathers_name,
            "Mother's Name"           => fn (Member $m) => $m->mothers_name,
            'Date of Salvation'       => fn (Member $m) => $m->date_salvation?->toDateString(),
            'Date of Baptism'         => fn (Member $m) => $m->date_baptism?->toDateString(),
            'Member Since'            => fn (Member $m) => $m->member_since?->toDateString(),
            'Province'                => fn (Member $m) => $m->province?->name,
            'District'                => fn (Member $m) => $m->district?->name,
            'Sector'                  => fn (Member $m) => $m->sector?->name,
            'Cellule'                 => fn (Member $m) => $m->cellule?->name,
            'Cell'                    => fn (Member $m) => $m->cell?->name,
            'Village'                 => fn (Member $m) => $m->village?->name,
            'Occupations'             => fn (Member $m) => self::names($m->occupations),
            'Educations'              => fn (Member $m) => self::names($m->educations),
            'Faculties'               => fn (Member $m) => self::names($m->faculties),
            'Departments'             => fn (Member $m) => self::names($m->departments),
            'Church Responsibilities' => fn (Member $m) => self::names($m->churchResponsibilities),
            'Talents'                 => fn (Member $m) => self::names($m->talents),
            'Spiritual Gifts'         => fn (Member $m) => self::names($m->spiritualGifts),
        ];
    }

    /**
     * Builds an .xlsx workbook of the given members and streams it as a download.
     *
     * @param iterable<Member> $members
     */
    public static function download(iterable $members, string $filename): StreamedResponse
    {
        $spreadsheet = self::buildSpreadsheet($members);

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
            $spreadsheet->disconnectWorksheets();
        }, $filename, ['Content-Type' => self::CONTENT_TYPE]);
    }

    /** @param iterable<Member> $members */
    private static function buildSpreadsheet(iterable $members): Spreadsheet
    {
        $columns = self::columns();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()->setTitle(self::SHEET_TITLE);

        $sheet->fromArray(array_keys($columns), null, 'A1');

        $row = 2;
        foreach ($members as $member) {
            $values = array_map(fn (callable $resolve) => $resolve($member), array_values($columns));
            // Written as explicit strings so long numbers (national ID, phone) are not turned into scientific notation.
            foreach ($values as $index => $value) {
                $sheet->setCellValueExplicit(
                    [$index + 1, $row],
                    $value,
                    is_int($value) ? DataType::TYPE_NUMERIC : DataType::TYPE_STRING
                );
            }
            $row++;
        }

        $lastColumn = Coordinate::stringFromColumnIndex(count($columns));
        $sheet->getStyle("A1:{$lastColumn}1")->getFont()->setBold(true);
        $sheet->freezePane('A2');
        $sheet->setAutoFilter("A1:{$lastColumn}" . max($row - 1, 1));

        foreach (range(1, count($columns)) as $index) {
            $sheet->getColumnDimensionByColumn($index)->setAutoSize(true);
        }

        return $spreadsheet;
    }

    private static function names(Collection $related): string
    {
        return $related->pluck('name')->unique()->implode(', ');
    }
}
