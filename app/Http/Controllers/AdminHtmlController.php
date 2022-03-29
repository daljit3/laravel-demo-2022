<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminHtmlController extends Controller
{
    public function loadHtml(Request $request)
    {
        $uStartDate = $request->query('dtstart');
        $uEndDate   = $request->query('dtend');

        try {
            $startDate  = Carbon::parse($uStartDate)->format('Y-m-d');
            $endDate    = Carbon::parse($uEndDate)->format('Y-m-d');

        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            flash($e->getMessage())->error();
            return redirect()->route('admin.home');
        }

        $filename = config('glide.filename_data_html');

        $url = Str::of(config('glide.national_grid_api_url'))->swap(['START_DATE' => $startDate, 'END_DATE' => $endDate,]);

        try {
            // Fetch webpage and save it locally
            $response = Http::get($url);
            Storage::put($filename, $response->body());

            flash('Fresh data loaded.')->success();
            return redirect()->route('admin.parse.html-csv');

        } catch (\Exception $e) {
            flash('There was an error loading data: '.$e->getMessage())->error();
        }
    }

    public function parseHtmlToCsv()
    {
        $filename_html = config('glide.filename_data_html');
        $filename_csv = config('glide.filename_data_csv');

        if(!Storage::exists($filename_html)) {
            flash("The data file doesn't exist.")->error();
            return redirect()->route('admin.home');
        }
        //if the csv file doesn't exist, then just create an empty one
        if(!Storage::exists($filename_csv)) {
            $contents = "";
            Storage::put($filename_csv, $contents);
        }

        $contentHtml = Storage::get($filename_html);

        // Extract data
        libxml_use_internal_errors(true); // hide any markup errors
        $doc = new \DOMDocument();
        $doc->loadHTML($contentHtml);
        $xpath = new \DOMXPath($doc);

        $handleCsv = fopen(Storage::path($filename_csv), 'w');
        # Header row
        fputcsv($handleCsv, [
            "Applicable At",
            "Applicable For",
            "Data Item",
            "Value",
            "Generated Time",
            "Quality Indicator",
        ]);

        foreach($xpath->query('//table[@id="DataResult"]//tbody')->item(0)->getElementsByTagName('tr') as $rows) {
            $cells = $rows->getElementsByTagName('td');

            $applicable_at = ""; //Not required for now //$cells->item(0)->textContent;
            $applicable_for = $cells->item(1)->textContent;
            $area = $cells->item(2)->textContent;
            $value = $cells->item(3)->textContent;
            $generated_time = ""; // Not required for now
            $qual_indicator = ""; // Not required for now

            $data = [$applicable_at, $applicable_for, $area, $value, $generated_time, $qual_indicator];
            fputcsv($handleCsv, $data);
        }
        fclose($handleCsv);

        flash("HTML to CSV saved.");
        return redirect()->route('admin.parse.csv-db');
    }
}
