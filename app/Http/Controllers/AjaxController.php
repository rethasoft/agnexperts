<?php

namespace App\Http\Controllers;

use App\Models\Keuringen;
use Illuminate\Http\Request;
use App\Models\Type;
use App\Models\Client;
use App\Models\PriceList;
use App\Models\Employee;
use App\Models\EmployeEvent;
use App\Models\File;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendSingleFile;
use App\Models\KeuringenDetail;

class AjaxController extends Controller
{
    public function getTypesByClient($client_id)
    {
        try {


            $client = Client::find($client_id);
            $html = '<option value="0">Selecteren</option>';

            // Debug için types verilerini kontrol edelim
            $types = Type::where('tenant_id', $client->tenant_id)
                ->where('category_id', 0)
                ->get();

            foreach ($types as $type) {

                if ($type->subTypes->count() > 0) {
                    $html .= '<optgroup label="' . $type->name . '">';
                    foreach ($type->subTypes as $subType) {
                        $price_list = PriceList::where([
                            'type_id' => $subType->id,
                            'client_id' => $client->id
                        ])->first();
                        $subType->price = $price_list ? $price_list->price : $subType->price;

                        $subType->category_name = $type->short_name;
                        $html .= '<option value="' . $subType->id . '" data-product="' . htmlspecialchars(json_encode($subType)) . '">' . $subType->name . '</option>';

                    }
                    $html .= '</optgroup>';
                } else {
                    $price_list = PriceList::where([
                        'type_id' => $type->id,
                        'client_id' => $client->id
                    ])->first();
                    $type->price = $price_list ? $price_list->price : $type->price;

                    $html .= '<option value="' . $type->id . '" data-product="' . htmlspecialchars(json_encode($type)) . '">' . $type->name . '</option>';

                }
            }

            Log::info('Final HTML: ' . $html);
            return response()->json(['msg' => 'Success', 'data' => $html]);
        } catch (\Throwable $th) {
            Log::error('Error in getTypesByClient: ' . $th->getMessage());
            return response()->json(['msg' => 'Client could not find']);
        }
    }
    public function getSales($interval)
    {
        try {
            // Modify your Laravel query to aggregate data by month for the year 2024
            $data = Keuringen::join('keuringen_details', 'keuringens.id', '=', 'keuringen_details.keuringen_id')
                ->select(
                    DB::raw('MONTH(keuringens.created_at) as month'),
                    DB::raw('YEAR(keuringens.created_at) as year'),
                    'keuringen_details.type_id',
                    DB::raw('COUNT(*) as total')
                )
                ->where('keuringens.tenant_id', getTenantId())
                ->whereYear('keuringens.created_at', date('Y'))
                ->groupBy('year', 'month', 'keuringen_details.type_id')
                ->get();

            // Initialize an array to store aggregated counts
            $aggregatedData = [];

            // Iterate through the fetched data and aggregate counts for each unique combination of year, month, and type
            foreach ($data as $item) {
                $year = $item->year;
                $month = $item->month;
                $typeId = $item->type_id;
                $total = $item->total;

                // Create a unique key for grouping
                $key = "{$year}-{$month}-{$typeId}";

                // If the key already exists in the aggregated data, add the total count to it
                // Otherwise, initialize it with the total count
                if (isset($aggregatedData[$key])) {
                    $aggregatedData[$key] += $total;
                } else {
                    $aggregatedData[$key] = $total;
                }
            }

            // Transform the aggregated data into the desired format
            $formattedData = [];

            foreach ($aggregatedData as $key => $value) {
                list($year, $month, $typeId) = explode('-', $key);
                $formattedData[] = [
                    'year' => $year,
                    'month' => $month,
                    'type_id' => $typeId,
                    'total' => $value,
                ];
            }

            // $formattedData now contains the aggregated counts grouped by year, month, and type


            // Prepare the data for Chart.js
            $labels = []; // For x-axis (year and month)
            $datasets = []; // For y-axis bars (nested by type)

            // Iterate through the formatted data to populate labels and datasets
            foreach ($formattedData as $item) {
                $yearMonthLabel = $item['year'] . '-' . $item['month']; // Combine year and month as label
                $typeId = $item['type_id'];
                $total = $item['total'];

                // If the label doesn't exist in $labels array, add it
                if (!in_array($yearMonthLabel, $labels)) {
                    $labels[] = $yearMonthLabel;
                }

                // Check if dataset for the type already exists, if not create a new one
                if (!isset($datasets[$typeId])) {
                    $type = Type::find($typeId);
                    $datasets[$typeId] = [
                        'label' => $type->name, // Label for the type
                        'data' => [], // Initialize data array for the type
                    ];
                }

                // Add the total count to the corresponding label and type
                $datasets[$typeId]['data'][] = $total;
            }

            // Convert $datasets array to simple array format for Chart.js
            $datasets = array_values($datasets);

            // / Calculate totals for each month
            $totals = [];
            foreach ($datasets as $dataset) {
                foreach ($dataset['data'] as $index => $count) {
                    $month = $labels[$index];
                    if (!isset($totals[$month])) {
                        $totals[$month] = 0;
                    }
                    $totals[$month] += $count;
                }
            }


            // Return the data as JSON response
            return response()->json([
                'labels' => $labels,
                'datasets' => $datasets,
                'totals' => $totals
            ]);
        } catch (\Throwable $th) {
            return response()->json(['msg' => $th->getMessage()]);
        }
    }



    public function getSalesByPartner($interval)
    {
        try {
            // Modify your Laravel query to aggregate data by month for the year 2024
            $data = Keuringen::join('keuringen_details', 'keuringens.id', '=', 'keuringen_details.keuringen_id')
                ->select(
                    DB::raw('MONTH(keuringens.created_at) as month'),
                    DB::raw('YEAR(keuringens.created_at) as year'),
                    'keuringens.client_id',
                    DB::raw('COUNT(*) as total')
                )
                ->where('keuringens.tenant_id', getTenantId())
                ->whereYear('keuringens.created_at', date('Y'))
                ->groupBy('year', 'month', 'keuringens.client_id')
                ->get();

            // Initialize an array to store aggregated counts
            $aggregatedData = [];

            // Iterate through the fetched data and aggregate counts for each unique combination of year, month, and type
            foreach ($data as $item) {
                $year = $item->year;
                $month = $item->month;
                $client_id = $item->client_id;
                $total = $item->total;

                // Create a unique key for grouping
                $key = "{$year}-{$month}-{$client_id}";

                // If the key already exists in the aggregated data, add the total count to it
                // Otherwise, initialize it with the total count
                if (isset($aggregatedData[$key])) {
                    $aggregatedData[$key] += $total;
                } else {
                    $aggregatedData[$key] = $total;
                }
            }

            // Transform the aggregated data into the desired format
            $formattedData = [];

            foreach ($aggregatedData as $key => $value) {
                list($year, $month, $client_id) = explode('-', $key);
                $formattedData[] = [
                    'year' => $year,
                    'month' => $month,
                    'client_id' => $client_id,
                    'total' => $value,
                ];
            }
            // $formattedData now contains the aggregated counts grouped by year, month, and type


            // Prepare the data for Chart.js
            $labels = []; // For x-axis (year and month)
            $datasets = []; // For y-axis bars (nested by type)

            // Iterate through the formatted data to populate labels and datasets
            foreach ($formattedData as $item) {
                $yearMonthLabel = $item['year'] . '-' . $item['month']; // Combine year and month as label
                $client_id = $item['client_id'];
                $total = $item['total'];

                // If the label doesn't exist in $labels array, add it
                if (!in_array($yearMonthLabel, $labels)) {
                    $labels[] = $yearMonthLabel;
                }

                // Check if dataset for the type already exists, if not create a new one
                if (!isset($datasets[$client_id])) {
                    $client = Client::find($client_id);
                    $datasets[$client_id] = [
                        'label' => $client->name,
                        'data' => [], // Initialize data array for the type
                    ];
                }

                // Add the total count to the corresponding label and type
                $datasets[$client_id]['data'][] = $total;
            }

            // Convert $datasets array to simple array format for Chart.js
            $datasets = array_values($datasets);

            // / Calculate totals for each month
            $totals = [];
            foreach ($datasets as $dataset) {
                foreach ($dataset['data'] as $index => $count) {
                    $month = $labels[$index];
                    if (!isset($totals[$month])) {
                        $totals[$month] = 0;
                    }
                    $totals[$month] += $count;
                }
            }


            // Return the data as JSON response
            return response()->json([
                'labels' => $labels,
                'datasets' => $datasets,
                'totals' => $totals
            ]);
        } catch (\Throwable $th) {
            return response()->json(['msg' => $th->getMessage()]);
        }
    }
    public function getEmployes()
    {
        return Employee::select('id', 'name', 'surname')->where('tenant_id', getTenantId())->get();
    }
    public function getEvents()
    {
        $data = EmployeEvent::all();
        return response()->json($data);
        $items = [];
        if ($data->count() > 0) {
            foreach ($data as $item) {
                $title = $item->title;
                $title .= $item->employe ? ' ' . $item->employe->name . ' ' . $item->employe->surname : '';

                $items[] = [
                    'id' => $item->id,
                    'title' => $title,
                    'start' => $item->start,
                    'end' => $item->end,
                    'employe' => $item->employe ? ' ' . $item->employe->name . ' ' . $item->employe->surname : '',
                    'adres' => $item->keuringen ? $item->keuringen->street . ' ' . $item->keuringen->number . ' ' . $item->keuringen->postal_code . ' ' . $item->keuringen->district : '',
                    'start_date' => date('d-m-Y H:i', strtotime($item->start)),
                    'file_id' => $item->keuringen ? $item->keuringen->file_id : '',
                    'keuringen_id' => $item->keuringen_id > 0 ? $item->keuringen_id : ''
                ];
            }
        }
        return $items;
    }

    public function updateEvent(Request $request)
    {
        $data = $request->json()->all();
        $item = EmployeEvent::findOrFail($data['id']);

        $arr = [];
        $arr['start'] = date('Y-m-d H:i:s', strtotime($data['start']));
        if ($data['end'] != '') {
            $arr['end'] = $item['end'];
        }
        $updateItem = $item->update($arr);
        if ($updateItem) {
            return response()->json([
                'status' => 'success',
                'message' => 'Evenement succesvol bijgewerkt',
                'data' => $item
            ], 200);
        } else {
            return response()->json(['msg' => 'Er is iets fout gegaan']);
        }
    }
    public function deleteEvent(Request $request)
    {
        $employeEvent = EmployeEvent::findOrFail($request->id);
        $employeEvent->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Deleted successfully'
        ], 200);
    }
    public function deleteFile($id)
    {
        try {
            $file = File::findOrFail($id);
            // Delete the file from the public storage directory
            $filePath = public_path($file->path . $file->name); // Assuming filename is stored in the database
            if (file_exists($filePath)) {
                unlink($filePath); // Delete the file
            }
            $filename = $file->name;
            $file->delete();
            createComment('Keuringen', 'Delete', $file->object_id, $filename . ' verwijderd');

            return back()->with('msg', 'Bestand verwijderd');
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['msg' => 'Bestand niet gevonden']);
        } catch (\Throwable $th) {
            Log::error('Ajax deleteFile: ', $th->getMessage());
            return back()->withErrors(['msg' => 'Er is iets fout gegaan']);
        }
    }

    public function sendFile($id)
    {
        try {
            $file = File::findOrFail($id);
            $filePath = public_path($file->path . $file->name);
            if (!Mail::to($file->keuringen->email)->send(new SendSingleFile($filePath, $file->keuringen->file_id))) {
                return back()->withErrors(['msg' => 'E-mail kon niet worden verzonden']);
            }
            $file->update(['email_send_status' => 1]);
            return back()->with('msg', 'Bestand verwijderd');
        } catch (\Throwable $th) {
            Log::error('Ajax sendFile: ', $th->getMessage());
            return back()->withErrors(['msg' => 'Er is iets fout gegaan']);
        }
    }
    public function deleteType($id, $type_id)
    {
        try {
            KeuringenDetail::where(['keuringen_id' => $id, 'type_id' => $type_id])->delete();
            return back()->with('msg', 'Dienst verwijderd');
        } catch (\Throwable $th) {
            return back()->withErrors(['msg' => 'Dienst niet gevonden']);
        }
    }

    public function assignInspection(Request $request)
    {
        try {
            // Validate the incoming request data
            $validated = $request->validate([
                'inspection_id' => 'required',
                'employee_id' => 'required'
            ]);

            // Find the inspection (assuming it's related to EmployeEvent or Keuringen)
            $inspection = EmployeEvent::where('id', $validated['inspection_id'])
                ->orWhere('keuringen_id', $validated['inspection_id'])
                ->first();

            if (!$inspection) {
                // If not found in EmployeEvent, try to find in Keuringen
                $inspection = Keuringen::find($validated['inspection_id']);

                if (!$inspection) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Inspection not found'
                    ], 404);
                }
            }

            // Find the employee
            $employee = Employee::find($validated['employee_id']);
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found'
                ], 404);
            }

            // Update the inspection with the new employee assignment
            $inspection->employee_id = $validated['employee_id'];
            $inspection->save();

            // Log the assignment
            Log::info("Inspection #{$inspection->id} assigned to employee #{$employee->id} ({$employee->name} {$employee->surname})");

            // Return success with employee name for UI update
            return response()->json([
                'success' => true,
                'message' => 'Inspection assigned successfully',
                'employee_name' => $employee->name . ' ' . $employee->surname,
                'employee_id' => $employee->id
            ]);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error assigning inspection: ' . $e->getMessage());

            // Return error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while assigning the inspection: ' . $e->getMessage()
            ], 500);
        }
    }
}
