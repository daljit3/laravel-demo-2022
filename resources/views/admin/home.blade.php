@extends('layouts.appAdmin')

@include('scripts_datetimepicker')

@section('content')

    <div class="container">
        <div style="display: flex;justify-content: space-between;">
            <h3 class="mb-4">Admin Portal</h3>
            <a href="{{ route('dashboard') }}">Dashboard</a>
        </div>

        <hr />
        <div class="row">
            <div class="col">
                <h3 class="mb-4">Fetch Data</h3>

                <div class="card">
                    <div class="card-header">Fetch National Grid's Data</div>
                    <div class="card-body">

                        <!--
                        @if($html_size != "")
                            File size: {{ humanBytes($html_size) }} <br />
                            Loaded at: {{ date('d/m/y H:i', $html_time) }}
                        @else
                            <span style="color:#ccc;">No data.</span>
                        @endif
                        -->

                        <br />
                        <form action="{{ route('admin.load.html') }}" method="get">
                            <div class="row">
                                <div class="col-6">
                                    <div class="input-group input-group-sm mb-3">
                                        <span class="input-group-text">Start date</span>
                                        <input type="text" name="dtstart" id="dtstart" class="form-control" value="2022-01-01">
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <span class="input-group-text">End date</span>
                                        <input type="text" name="dtend" id="dtend" class="form-control" value="<?=date('Y-m-d')?>">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info">Fetch Data</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col">
                <h3 class="mb-4">Upload CSV File</h3>

                <div class="card">
                    <div class="card-header">Upload National Grid's CSV Data File</div>
                    <div class="card-body">
                        <form method="post" action="{{ route('admin.upload.process') }}" enctype="multipart/form-data">
                            @csrf

                            <br />
                            <div class="input-group mb-3">
                                <input type="file" name="csvfile" class="form-control" id="inputGroupFile02">
                                <label class="input-group-text" for="inputGroupFile02">Upload</label>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-warning" role="alert">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <br />
                            <button class="btn btn-info my-2" type="submit">Submit</button>
                        </form>
                    </div>
                </div>
            </div>

        </div> <!-- /.row -->
    </div>

    <script>
        $(document).ready(function() {
            jQuery(function(){
                jQuery('#dtstart').datetimepicker({
                    format:'Y-m-d',
                    onShow:function( ct ){
                        this.setOptions({
                            maxDate:jQuery('#dtend').val()?jQuery('#dtend').val():false
                        })
                    },
                    timepicker:false
                });
                jQuery('#dtend').datetimepicker({
                    format:'Y-m-d',
                    onShow:function( ct ){
                        this.setOptions({
                            minDate:jQuery('#dtstart').val()?jQuery('#dtstart').val():false
                        })
                    },
                    timepicker:false
                });
            });
        });
    </script>
@endsection
