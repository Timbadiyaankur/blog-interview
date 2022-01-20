<?php

namespace App\Helpers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Http\Response;
use App\Helpers\MimeTypes;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Export
{

    const EXTENTIONS = [
        'xlsx' => 'Xlsx',
        'xls' => 'Xls',
        'csv' => 'Csv',
    ];

    public $type;
    public $ext;

    protected $file_type;
    protected $reader;
    protected $worksheet_info;
    protected $path;
    public $spreadsheet;
    public $sheet;
    public $i = 1;

    public function __construct($ext, $new = true)
    {
        $this->ext = $ext;
        $this->type = $this::EXTENTIONS[$ext];
        if ($new) {
            $this->spreadsheet = new Spreadsheet();
            $this->sheet = $this->spreadsheet->getActiveSheet();
            $this->sheet->setTitle('Sheet0');
        }
    }

    public function addNewSheet($i)
    {
        $this->sheet = $this->spreadsheet->createSheet();
        $this->sheet->setTitle('Sheet' . $i);
        return $this;
    }

    public function fromArrays($array)
    {
        foreach ($array as $data) {
            $this->sheet->fromArray($data, null, 'A' . $this->i);
            $this->i++;
        }
        return $this;
    }

    public function fromArray($data, $m = 0)
    {
        if ($m == 1) {
            $this->i = 1;
        }
        $this->sheet->fromArray($data, null, 'A' . $this->i);
        $this->i++;
        return $this;
    }

    public function save($name)
    {
        $writer = IOFactory::createWriter($this->spreadsheet, $this->type);
        return $writer->save($name);
    }

    public static function readFile($path)
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if (file_exists($path)) {
            $export = new Self($ext, true);
            $export->identifyFile($path)
                ->createReader()
                ->loadSheetInfo()
                ->loadFile()
                ->getActiveSheet();
            return $export;
        } else {
            return new Self($ext);
        }
    }

    public function identifyFile($path = null)
    {
        if ($path) {
            $this->path = $path;
        }
        $this->file_type = IOFactory::identify($this->path);
        return $this;
    }

    public function createReader()
    {
        $this->reader = IOFactory::createReader($this->file_type);
        return $this;
    }

    public function loadSheetInfo()
    {
        $this->worksheet_info = $this->reader->listWorksheetInfo($this->path);
        return $this;
    }

    public function loadFile()
    {
        $this->spreadsheet = $this->reader->load($this->path);
        return $this;
    }

    public function getCountSheet()
    {
        return $this->spreadsheet->getSheetCount();
    }

    public function getActiveSheet()
    {
        $this->sheet = $this->spreadsheet->getActiveSheet();
        return $this;
    }

    public function download($name)
    {
        $streamedResponse = new StreamedResponse();
        $streamedResponse->setCallback(function () {
            $writer = IOFactory::createWriter($this->spreadsheet, $this->type);
            $writer->save('php://output');
        });
        $streamedResponse->setStatusCode(Response::HTTP_OK);
        $streamedResponse->headers->set('Content-Type', MimeTypes::getMimeType($this->ext));
        $streamedResponse->headers->set('Content-Disposition', 'attachment; filename="' . $name . '"');
        return $streamedResponse->send();
    }
}