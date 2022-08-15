<?php


namespace Modules\Certificate\Services;


use App\Helpers\DateHelper;
use Exception;
use Modules\Certificate\Dto\CoordsDto;
use Modules\Certificate\Dto\FontDto;
use Modules\Certificate\Dto\TemplateParamsDto;
use Modules\Certificate\Helpers\AbcHelper;
use Modules\File\Services\FileService;
use Modules\User\Facades\UserFacade;
use Modules\User\Models\User;
use Neti\Laravel\Files\Models\File;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\Filter\FilterException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use setasign\Fpdi\PdfReader\PdfReaderException;

class CertificateGeneratorService
{
    /**
     * @var FileService
     */
    private FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->defineFontPath();
        $this->fileService = $fileService;
    }

    /**
     * @param User $user
     * @param string $templatePath
     * @return File
     * @throws CrossReferenceException
     * @throws FilterException
     * @throws PdfParserException
     * @throws PdfReaderException
     * @throws PdfTypeException
     * @throws Exception
     */
    public function generate(User $user, string $templatePath): File
    {
        $path = $this->createForUserByTemplate($user, $templatePath);
        return $this->fileService->createByPath($path);
    }

    /**
     * @param User $user
     * @param string $templatePath
     * @return string
     * @throws CrossReferenceException
     * @throws FilterException
     * @throws PdfParserException
     * @throws PdfReaderException
     * @throws PdfTypeException
     * @throws Exception
     */
    protected function createForUserByTemplate(User $user, string $templatePath): string
    {
        $font = $this->getFont();
        $pdf = new Fpdi();
        $pdf->AddFont($font->family, '', $font->file);
        $pdf->AddPage();
        $this->setTemplate($pdf, $templatePath);

        $this->addName($pdf, $font, $user);
        $this->addDate($pdf, $font);

        $path = $this->getTempPath($user->id . '_generated.pdf');
        $pdf->output('F', $path);

        return $path;
    }

    /**
     * @param Fpdi $pdf
     * @param string $templatePath
     * @throws CrossReferenceException
     * @throws FilterException
     * @throws PdfParserException
     * @throws PdfReaderException
     * @throws PdfTypeException
     */
    private function setTemplate(Fpdi $pdf, string $templatePath): void
    {
        $pdf->setSourceFile($templatePath);
        $tplIdx = $pdf->importPage(1);
        $templateParams = $this->getTemplateParams();
        $pdf->useTemplate(
            $tplIdx,
            $templateParams->x,
            $templateParams->y,
            $templateParams->width,
            $templateParams->height,
            $templateParams->adjustPageSize
        );
    }

    /**
     * @param Fpdi $pdf
     * @param FontDto $font
     * @param User $user
     */
    private function addName(Fpdi $pdf, FontDto $font, User $user)
    {
        $pdf->SetFont($font->family, '', $font->size);
        $pdf->SetTextColor(0, 0, 0);
        $name = UserFacade::fullName($user, false);
        $coords = $this->calculateCoords($name);
        $pdf->setXY($coords->x, $coords->y);
        $pdf->Write(0, iconv('utf-8', 'windows-1251', $name));
    }

    /**
     * @param Fpdi $pdf
     * @param FontDto $font
     * @throws Exception
     */
    private function addDate(Fpdi $pdf, FontDto $font)
    {
        $pdf->SetFont($font->family, '', 60);
        $pdf->SetTextColor(111, 195, 6);
        $pdf->setXY(470, 980);
        $pdf->Write(3, DateHelper::formatDate(DateHelper::now(), 'Y'));
    }

    /**
     * @param string $fileName
     * @return string
     */
    protected function getTempPath(string $fileName): string
    {
        $dir = storage_path('app/public/temp_certificates');
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        return $dir . '/' . $fileName;
    }

    /**
     * @return FontDto
     */
    protected function getFont(): FontDto
    {
        return FontDto::populateByArray([
            'family' => 'Arial',
            'file'   => 'arial.php',
            'size'   => 90
        ]);
    }

    protected function defineFontPath()
    {
        define('FPDF_FONTPATH', base_path() . '/Modules/Certificate/Resources/Fonts');
    }

    /**
     * @param string $name
     * @return CoordsDto
     */
    private function calculateCoords(string $name): CoordsDto
    {
        $width = 0;
        $totalWidth = 4000;
        //пиксели в пдф и на макете транслируются с отклонением с этим коэфициентом
        $coef = 0.24;
        for ($i = 0; $i < mb_strlen($name); $i++) {
            $char = mb_substr($name, $i, 1);
            $width += AbcHelper::getLetterWidth($char, 40);
        }

        $x = round($coef * ($totalWidth - $width) / 2);

        return CoordsDto::populateByArray([
            'x' => $x >= 0 ? $x : 0,
            'y' => 700
        ]);
    }

    /**
     * @return TemplateParamsDto
     */
    private function getTemplateParams(): TemplateParamsDto
    {
        return TemplateParamsDto::populateByArray([
            'x' => 0,
            'y' => 0,
            'width' => 1485,
            'height' => 1050,
            'adjustPageSize' => true,
        ]);
    }
}
