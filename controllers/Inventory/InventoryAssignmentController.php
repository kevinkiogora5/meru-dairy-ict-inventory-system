<?php

namespace Mcdcu\Projects\controllers\Inventory;

use Mcdcu\Projects\services\employees\employeesService;
use Mcdcu\Projects\services\Inventory\InventoryAssignmentService;
use Mcdcu\Projects\services\Inventory\InventoryItemService;
use Mcdcu\Projects\services\location\locationsService;
use sigawa\mvccore\Application;
use sigawa\mvccore\Request;
use sigawa\mvccore\Response;
use sigawa\mvccore\Controller;
use sigawa\mvccore\exception\ValidationException;
use sigawa\mvccore\middlewares\AuthMiddleware;
use sigawa\mvccore\middlewares\NavigationMiddleware;

class InventoryAssignmentController extends Controller
{
    protected InventoryAssignmentService $service;
    protected InventoryItemService $itemService;
    protected employeesService $employeeService;
    protected locationsService $locationService;

    public function __construct()
    {
        $this->service = new InventoryAssignmentService();
        $this->itemService = new InventoryItemService();
        $this->employeeService = new employeesService();
        $this->locationService = new locationsService();
         $this->registerMiddleware(new NavigationMiddleware([
            '/index' 
        ],'/login'));
         $this->registerMiddleware(new AuthMiddleware());
    }

    public function index()
    {
        $items = $this->itemService->getAll();
        $employees = $this->employeeService->getAll();
        $locations = $this->locationService->getAll();
        $assignments = $this->service->getAll();
        $this->setLayout("admin");
        return $this->render('assignment', ['assignments' => $assignments,
        'employees'=> $employees,'locations'=> $locations,'items'=>$items]);
    }
public function getUnassignedItems(Request $request, Response $response)
{
    try {
        $items = $this->itemService->getUnassignedItems(); 
        return $response->json($items);
    } catch (\Exception $e) {
        return $response->json(['error' => $e->getMessage()], 500);
    }
}

   
    public function create(Request $request, Response $response)
    {
        if ($request->isPost()) {
            $data = $request->getBody();
            try { 
                                   $user = Application::$app->user;
    if (!$user) {
        throw new ValidationException(['user' => 'User must be logged in to create a department.']);
    }
    $data['issued_by'] = $user->id;

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
        if ($request->isPost()) {
            $data = $request->getBody();
            try {
                $item = $this->service->update((int)$id, $data);
                return $response->json(['message' => 'Assignment updated successfully.', 'data' => $item]);
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

   public function actionSearch(Request $request, Response $response)
{
    try {
        $result = $this->service->searchAndPaginate($request->getBody());

        // Set the layout
        $this->setLayout("admin");

        // Render the card-based search result view
        return $this->render('search', [
            'assignments' => $result['data'],
            'page' => $result['page'],
            'perPage' => $result['perPage'],
            'total' => $result['total'],
            'lastPage' => $result['lastPage']
        ]);
    } catch (ValidationException $th) {
        return $response->json(['error' => $th->errors], 400);
    } catch (\Exception $e) {
        return $response->json(['error' => $e->getMessage()], 500);
    }
}
public function list(Request $request, Response $response)
{
    try {
        $assignments = $this->service->getAll(); // Or your own filtering logic

        return $response->json($assignments);
    } catch (\Exception $e) {
        return $response->json(['error' => 'Failed to fetch assignments.'], 500);
    }
}
public function report(Request $request, Response $response)
{
    $type = $request->getQueryParams()['type'] ?? 'pdf';
    $filters = [
        'employee_id' => $request->getQueryParams()['employee_id'] ?? null,
        'location_id' => $request->getQueryParams()['location_id'] ?? null,
        'created_at'  => $request->getQueryParams()['created_at'] ?? null,
    ];

    try {
        // Fetch filtered assignments
        $assignments = $this->service->getAll($filters);

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

            // Table headers (row 7)
            $startRow = 7;
            $headers = ['Employee', 'Inventory Item', 'Location', 'Notes', 'Assigned At'];
            $col = 'B';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . $startRow, $header);
                $col++;
            }

            // Fill table data
            $row = $startRow + 1;
            foreach ($assignments as $a) {
                $sheet->setCellValue('B' . $row, $a->employees_email ?? '');
                $sheet->setCellValue('C' . $row, $a->inventory_item_name ?? '');
                $sheet->setCellValue('D' . $row, $a->location_name ?? '');
                $sheet->setCellValue('E' . $row, $a->notes ?? '');
                $sheet->setCellValue('F' . $row, $a->created_at ?? '');
                $row++;
            }

            // Download Excel
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="assignment_report.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;

        } else {
            // PDF via TCPDF
            $pdf = new \TCPDF('L');
            $pdf->SetCreator('Inventory System');
            $pdf->SetAuthor('Inventory System');
            $pdf->SetTitle('Assignment Report');
            $pdf->SetMargins(10, 15, 10);
            $pdf->AddPage();
            $pdf->SetFont('helvetica', '', 10);

            // Logo
            $logoFile = __DIR__ . '/../../public/assets/logo/OIP.jpg';
            if (file_exists($logoFile)) {
                $pdf->Image($logoFile, 25, 15, 40);
            }

            // Company Info
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
            $title = 'Assignment Report';
            if ($filters['employee_id']) {
                $title .= ' - Employee: ' . ($assignments[0]->employees_email ?? 'N/A');
            }
            if ($filters['location_id']) {
                $title .= ' - Location: ' . ($assignments[0]->location_name ?? 'N/A');
            }
            if ($filters['created_at']) {
                $title .= ' - Date: ' . $filters['created_at'];
            }

            $pdf->SetFont('helvetica', 'B', 14);
            $pdf->Cell(0, 10, $title, 0, 1, 'C');
            $pdf->Ln(5);

            // Table headers
            $headers = ['Employee', 'Inventory Item', 'Location', 'Notes', 'Assigned At'];
            $colCount = count($headers);
            $tableWidth = $pdf->getPageWidth() - $pdf->getMargins()['left'] - $pdf->getMargins()['right'];
            $colWidth = $tableWidth / $colCount;

            $pdf->SetFont('helvetica', 'B', 10);
            foreach ($headers as $header) {
                $pdf->Cell($colWidth, 10, $header, 1, 0, 'C');
            }
            $pdf->Ln();

            // Table rows
            $pdf->SetFont('helvetica', '', 9);
            foreach ($assignments as $a) {
                $pdf->Cell($colWidth, 8, $a->employees_email ?? '', 1);
                $pdf->Cell($colWidth, 8, $a->inventory_item_name ?? '', 1);
                $pdf->Cell($colWidth, 8, $a->location_name ?? '', 1);
                $pdf->Cell($colWidth, 8, $a->notes ?? '', 1);
                $pdf->Cell($colWidth, 8, $a->created_at ?? '', 1);
                $pdf->Ln();
            }

            // Output PDF
            $pdf->Output('assignment_report.pdf', 'I');
            exit;
        }
    } catch (\Exception $e) {
        return $response->json(['error' => 'Failed to generate report: ' . $e->getMessage()], 500);
    }
}


}