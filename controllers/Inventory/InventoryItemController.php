<?php

namespace Mcdcu\Projects\controllers\Inventory;

use Mcdcu\Projects\services\Inventory\InventoryCategoriesService;
use Mcdcu\Projects\services\Inventory\InventoryItemService;
use sigawa\mvccore\Application;
use sigawa\mvccore\Request;
use sigawa\mvccore\Response;
use sigawa\mvccore\Controller;
use sigawa\mvccore\exception\ValidationException;
use sigawa\mvccore\middlewares\AuthMiddleware;
use sigawa\mvccore\middlewares\NavigationMiddleware;

class InventoryItemController extends Controller
{
       protected InventoryItemService $service;
       protected InventoryCategoriesService $services;

    public function __construct()
    {
        $this->service = new InventoryItemService();
        $this->services = new InventoryCategoriesService();
         $this->registerMiddleware(new NavigationMiddleware([
            '/index'
        ],'/login'));
         $this->registerMiddleware(new AuthMiddleware());
    }

    public function index()
    {
        $categories = $this->services->getAll();
        $items = $this->service->getAll();
        $this->setLayout("admin");
        return $this->render('goods', ['categories' => $categories, 'items' => $items]);
    }
    public function create(Request $request, Response $response)
    {
        if ($request->isPost()) {
            $data = $request->getBody();
            try {
                $user = Application::$app->user;
                if (!$user) {
                    throw new ValidationException(['user' => 'User must be logged in to create an inventory item.']);
                }
                $data['created_by'] = $user->id;

                $item = $this->service->create($data);
                return $response->json(['message' =>'Item created successfully.','data' => $item]);
            } catch (ValidationException $th) {
                //throw $th;
                return $response->json(['error' => $th->errors], 400);
            }
           
        }

        return $response->json(['error' => 'Invalid request method.'],400);
    }

    public function update(Request $request, Response $response, $id)
    {
        if ($request->isPut()|| $request->isPost()) {
            $data = $request->getBody();
            try {
                $item = $this->service->update((int)$id, $data);
                return $response->json(['message' => 'Item updated successfully.', 'data' => $item]);
            } catch (ValidationException $th) {
                return $response->json(['error' => $th->errors], 400);
            }
        }
          return $response->json(['error' => 'Invalid request method.'],400);
    }

    public function delete(Request $request, Response $response, $id)
    {
    if ($request->isDelete()) {
        try {
            $this->service->delete((int)$id);
            return $response->json(['message' => 'Item deleted successfully.']);
        } catch (ValidationException $th) {
            return $response->json(['error' => $th->errors], 400);
        }
    }
     return $response->json(['error' => 'Invalid request method.'],400);
    }
    public function list(Request $request, Response $response)
{
    try {
        $items = $this->service->getAll(); // Or your own filtering logic

        return $response->json($items);
    } catch (\Exception $e) {
        return $response->json(['error' => 'Failed to fetch items.'], 500);
    }
}
public function report(Request $request, Response $response)
{
    $type = $request->getQueryParams()['type'] ?? 'pdf';
    $filters = [
        'category_id' => $request->getQueryParams()['category_id'] ?? null,
        'created_at'  => $request->getQueryParams()['created_at'] ?? null
    ];

    try {
        $items = $this->service->getAll($filters); // Filtered inventory items

        if ($type === 'excel') {
            // Excel via PhpSpreadsheet
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Header logo
            $logo = __DIR__ . '/../../public/assets/logo/OIP.jpg';
            if (file_exists($logo)) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo');
                $drawing->setPath($logo);
                $drawing->setHeight(80);
                $drawing->setCoordinates('B1');
                $drawing->setWorksheet($sheet);
            }

            // Company info
            $sheet->mergeCells('B1:I5');
            $sheet->setCellValue(
                'B1',
                "MERU CENTRAL DAIRY CO-OPERATIVE UNION LIMITED\n" .
                "P.O. BOX 2919 MERU - 60200\n" .
                "TEL: 064-30081, 30082, 32494, 0733554040 | FAX: 064-30263\n" .
                "Email: maziwa@merudairy.co.ke | sales@merudairy.co.ke\n" .
                "Website: www.merudairy.co.ke"
            );
            $sheet->getStyle('B1')->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setWrapText(true);
            $sheet->getStyle('B1')->getFont()->setBold(true);

            // Table headers (start at row 7)
            $startRow = 7;
            $headers = ['Name', 'Model', 'Brand', 'Condition', 'Serial Number', 'Category', 'Created At'];
            $col = 'B';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . $startRow, $header);
                $col++;
            }

            // Fill table data
            $row = $startRow + 1;
            foreach ($items as $item) {
                $sheet->setCellValue('B' . $row, $item->name);
                $sheet->setCellValue('C' . $row, $item->model);
                $sheet->setCellValue('D' . $row, $item->brand);
                $sheet->setCellValue('E' . $row, $item->item_condition);
                $sheet->setCellValue('F' . $row, $item->serial_number);
                $sheet->setCellValue('G' . $row, $item->category_type);
                $sheet->setCellValue('H' . $row, $item->created_at);
                $row++;
            }

            // Download Excel
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="inventory_report.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;

        } else {
            // PDF via TCPDF
            $pdf = new \TCPDF('L'); // Landscape for better table fit
            $pdf->SetCreator('Inventory System');
            $pdf->SetAuthor('Inventory System');
            $pdf->SetTitle('Inventory Report');
            $pdf->SetMargins(10, 15, 10);
            $pdf->AddPage();
            $pdf->SetFont('helvetica', '', 10);

            // Header logo
           $logoFile = __DIR__ . '/../../public/assets/logo/OIP.jpg';
            if (file_exists($logoFile)) {
                $pdf->Image($logoFile, 25, 15, 40); // x,y,width
            }

            // Company info
            $html = <<<EOD
<h2 style="text-align:center;">MERU CENTRAL DAIRY CO-OPERATIVE UNION LIMITED</h2>
<p style="text-align:center; font-size:10px;">
P.O. BOX 2919 MERU - 60200<br>
TEL: 064-30081, 30082, 32494, 0733554040 | FAX: 064-30263<br>
Email: maziwa@merudairy.co.ke | sales@merudairy.co.ke<br>
Website: www.merudairy.co.ke
</p>
<hr>
EOD;
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->Ln(5);

            // Title
            $title = 'Inventory Items Report';
            if ($filters['category_id']) {
                $categoryName = !empty($items) ? $items[0]->category_type : 'N/A';
                $title .= ' - Category: ' . $categoryName;
            }
            if ($filters['created_at']) {
                $title .= ' - Date: ' . $filters['created_at'];
            }

            $pdf->SetFont('helvetica', 'B', 14);
            $pdf->Cell(0, 10, $title, 0, 1, 'C');
            $pdf->Ln(5);

            // Auto-fit table setup
            $headers = ['Name', 'Model', 'Brand', 'Condition', 'Serial No.', 'Category', 'Created At'];
            $colCount = count($headers);
            $tableWidth = $pdf->getPageWidth() - $pdf->getMargins()['left'] - $pdf->getMargins()['right'];
            $colWidth = $tableWidth / $colCount;

            // Table headers
            $pdf->SetFont('helvetica', 'B', 10);
            foreach ($headers as $header) {
                $pdf->Cell($colWidth, 10, $header, 1, 0, 'C');
            }
            $pdf->Ln();

            // Table rows
            $pdf->SetFont('helvetica', '', 9);
            foreach ($items as $item) {
                $pdf->Cell($colWidth, 8, $item->name, 1);
                $pdf->Cell($colWidth, 8, $item->model, 1);
                $pdf->Cell($colWidth, 8, $item->brand, 1);
                $pdf->Cell($colWidth, 8, $item->item_condition, 1);
                $pdf->Cell($colWidth, 8, $item->serial_number, 1);
                $pdf->Cell($colWidth, 8, $item->category_type, 1);
                $pdf->Cell($colWidth, 8, $item->created_at, 1);
                $pdf->Ln();
            }

            // Output PDF
            $pdf->Output('inventory_report.pdf', 'I');
            exit;
        }
    } catch (\Exception $e) {
        return $response->json(['error' => 'Failed to generate report: ' . $e->getMessage()], 500);
    }
}


}