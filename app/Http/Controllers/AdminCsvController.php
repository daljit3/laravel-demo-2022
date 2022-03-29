<?php

namespace App\Http\Controllers;

use App\Jobs\CalorificValuesCsvProcess;
use App\Models\CalorificValue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Facades\Storage;

class AdminCsvController extends Controller
{
    public function processUpload(Request $request)
    {
        $filename_csv = config('glide.filename_data_csv');
        $filename_csv_full_path = Storage::path($filename_csv);

        $request->validate([
            'csvfile' => 'required|mimes:csv'
        ]);

        $filename_key = 'csvfile';

        /* if (($request->hasFile($filename_key)) == false || $request->file($filename_key)->isValid() == false) {
            return back()->withErrors(['uploaderr' => 'There was an error uploading the csv file.']);
        }*/

        //Save the file to Storage/App
        $request->file($filename_key)->move(storage_path('app'), $filename_csv);

        flash("CSV file uploaded successfully.")->success();
        return redirect()->route('admin.parse.csv-db');
    }

    public function parseCsvToDb()
    {
        $filename_csv = config('glide.filename_data_csv');
        $filename_csv_full_path = Storage::path($filename_csv);

        if(!Storage::exists($filename_csv)) {
            flash("The CSV data file doesn't exist.")->error();
            return redirect()->route('admin.home');
        }

        $header = ['applicable_for', 'area', 'clf_value'];

        // Delete existing data
        CalorificValue::truncate();

        /****************** if you want to run it as queue/job enable this block *************
         * ****************** Don't forget to run "php artisan queue:work" *******************
         *
        $batch  = Bus::batch([])->dispatch();
        LazyCollection::make(function () use ($filename_csv_full_path) {
        $file = fopen($filename_csv_full_path, 'r');
        while ($data = fgetcsv($file)) {
        yield $data;
        }
        })->skip(1)
        ->chunk(5)
        ->each(function ($data) use($header, $batch) {
        $batch->add(new CalorificValuesCsvProcess($header, $data->toArray()));
        });
        return $batch;
         **************************************************************/

        $handle = fopen($filename_csv_full_path, 'r');
        if ($handle) {
            $count=0;
            while ($line = fgetcsv($handle)) {
                $count++;
                if($count == 1) {
                    continue; //skip header row
                }

                $data = [
                    Carbon::createFromFormat('d/m/Y', $line[1])->format('Y-m-d'), //applicable for
                    str_replace('Calorific Value, ', '', $line[2]),  // area
                    $line[3] // value
                ];

                // Add col name, and save to database
                $dbData = array_combine($header, $data);

                //Save to db
                CalorificValue::create($dbData);
            }
        }
        // Str::after($cells->item(2)->textContent, 'Calorific Value, ');
        flash("CSV data added to database")->success();
        return redirect()->route('admin.home');
    }
}
