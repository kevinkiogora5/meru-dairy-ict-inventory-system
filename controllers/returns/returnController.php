<?php

namespace Mcdcu\Projects\controllers\returns;

use Mcdcu\Projects\services\employees\employeesService;
use Mcdcu\Projects\services\Inventory\InventoryAssignmentService;
use Mcdcu\Projects\services\Inventory\InventoryItemService;
use Mcdcu\Projects\services\returns\returnService;
use sigawa\mvccore\Application;
use sigawa\mvccore\Request;
use sigawa\mvccore\Response;
use sigawa\mvccore\Controller;
use sigawa\mvccore\exception\ValidationException;
use sigawa\mvccore\middlewares\AuthMiddleware;
use sigawa\mvccore\middlewares\NavigationMiddleware;

class returnController extends Controller
{
    protected returnService $service;
    protected InventoryAssignmentService $inventoryService;
    protected InventoryItemService $itemService;
    protected employeesService $employeeService;

    public function __construct()
    {
        $this->service = new returnService();
        $this->itemService = new InventoryItemService();
        $this->employeeService = new employeesService();
        $this->inventoryService = new InventoryAssignmentService();
         $this->registerMiddleware(new NavigationMiddleware([
            '/index'
        ],'/login'));
         $this->registerMiddleware(new AuthMiddleware());
    }

    public function index()
    {  
        $items = $this->itemService->getAll();
        $employees = $this->employeeService->getEmployeesWithAssignments();
        $returns = $this->service->getAll();
        $this->setLayout("admin");
        return $this->render('returns', ['returns' => $returns, 
         'employees' => $employees, 'items' => $items]);
    }
   public function getAssignmentsByEmployee(Request $request, Response $response)
{
    $employeeId = (int) $request->getParam('employee_id');

    if (!$employeeId) {
        return $response->json([]);
    }

    $assignments = $this->inventoryService->getAssignmentsByEmployee($employeeId);

    return $response->json($assignments);
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
    $data['received_by'] = $user->id;
    $employeeId = $data['employee_id'] ?? null;
$assignmentId = $data['inventory_assignment_id'] ?? null;

if (!$employeeId || !$assignmentId) {
    throw new ValidationException(['employee_id or inventory_assignment_id missing']);
}

// Find the assignment that matches both
$assignment = $this->inventoryService->findAssignment($employeeId, $assignmentId);
if (!$assignment) {
    throw new ValidationException(['No assignment found for this employee and item.']);
}

$data['inventory_assignment_id'] = $assignment->id;
                $item = $this->service->create($data);
                return $response->json(['message' =>'Item ruturned successfully.','data' => $item]);
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
                return $response->json(['message' => 'return updated successfully.', 'data' => $item]);
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
        $returns = $this->service->getAll(); // Or your own filtering logic

        return $response->json($returns);
    } catch (\Exception $e) {
        return $response->json(['error' => 'Failed to fetch returns.'], 500);
    }
}
public function report(Request $request, Response $response)
{
    $type = $request->getQueryParams()['type'] ?? 'pdf';
    $filters = [
        'employee_id' => $request->getQueryParams()['employee_id'] ?? null,
        'created_at'  => $request->getQueryParams()['created_at'] ?? null,
    ];

    try {
        // Fetch filtered returns
        $returns = $this->service->getAll($filters);

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
            $headers = ['Employee', 'Return Item', 'Condition', 'Comments', 'Returned At'];
            $col = 'B';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . $startRow, $header);
                $col++;
            }

            // Fill table data
            $row = $startRow + 1;
            foreach ($returns as $r) {
                $sheet->setCellValue('B' . $row, $r->employee_email ?? '');
                $sheet->setCellValue('C' . $row, $r->inventory_item_name ?? '');
                $sheet->setCellValue('D' . $row, $r->returned_condition ?? '');
                $sheet->setCellValue('E' . $row, $r->comments ?? '');
                $sheet->setCellValue('F' . $row, $r->return_date ?? '');
                $row++;
            }

            // Download Excel
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="return_report.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;

        } else {
            // PDF via TCPDF
            $pdf = new \TCPDF('L');
            $pdf->SetCreator('Inventory System');
            $pdf->SetAuthor('Inventory System');
            $pdf->SetTitle('Return Report');
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
            $title = 'Return Report';
            if ($filters['employee_id']) {
                $title .= ' - Employee: ' . ($returns[0]->employee_email ?? 'N/A');
            }
            if ($filters['created_at']) {
                $title .= ' - Date: ' . $filters['created_at'];
            }

            $pdf->SetFont('helvetica', 'B', 14);
            $pdf->Cell(0, 10, $title, 0, 1, 'C');
            $pdf->Ln(5);

            // Table headers
            $headers = ['Employee', 'Return Item', 'Condition', 'Comments', 'Returned At'];
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
            foreach ($returns as $r) {
                $pdf->Cell($colWidth, 8, $r->employee_email ?? '', 1);
                $pdf->Cell($colWidth, 8, $r->inventory_item_name ?? '', 1);
                $pdf->Cell($colWidth, 8, $r->returned_condition ?? '', 1);
                $pdf->Cell($colWidth, 8, $r->comments ?? '', 1);
                $pdf->Cell($colWidth, 8, $r->return_date ?? '', 1);
                $pdf->Ln();
            }

            // Output PDF
            $pdf->Output('return_report.pdf', 'I');
            exit;
        }
    } catch (\Exception $e) {
        return $response->json(['error' => 'Failed to generate report: ' . $e->getMessage()], 500);
    }
}

}