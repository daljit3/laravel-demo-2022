<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessClfDataRequest;
use App\Models\CalorificValue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function dashboard()
    {
        $data_dates = CalorificValue::select(DB::raw(
                "ROUND(AVG(clf_value), 4) as avg_clf_value, DATE_FORMAT(MIN(applicable_for), '%d/%m/%Y') AS from_date, DATE_FORMAT(MAX(applicable_for), '%d/%m/%Y') AS to_date")
            )->get()->first();
        //$data_min_area = CalorificValue::select(DB::raw('area, MIN(clf_value)'))->groupBy('area')->get();

        return view('dashboard', compact(
            'data_dates',
            )
        );
    }

    public function processFormData(ProcessClfDataRequest $request, CalorificValue $clfrecord)
    {
        // Retrieve the validated input data...
        //$validatedData = $request->validated();
        $validatedData = $request->safe()->only(['applicablefor', 'area', 'clf_value']);

        $clfrecord->applicable_for = Carbon::createFromFormat('d/m/Y', $validatedData['applicablefor'])->format('Y-m-d');
        $clfrecord->area = $validatedData['area'];
        $clfrecord->clf_value = $validatedData['clf_value'];
        $clfrecord->save();

        return $validatedData;
    }

    public function processDeleteData(Request $request, CalorificValue $clfrecord)
    {
        $clfrecord->delete();

        return response()->json(['success' => true]);
    }
}
