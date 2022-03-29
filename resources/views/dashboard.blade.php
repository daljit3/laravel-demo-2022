@extends('layouts.app')

@section('content')

    <div class="container mt-5">
        <div style="display: flex;justify-content: space-between;">
            <div>
                Data Collection Period: <strong>{{ $data_dates->from_date }}</strong> to <strong>{{ $data_dates->to_date }}</strong>
                <br/>
                Average calorific value  across all areas during this period is <strong>{{ $data_dates->avg_clf_value }}</strong>
            </div>
            <div>
                <a href="{{ route('admin.home') }}">Admin</a>
            </div>

        </div>

        <div class="row mt-3 justify-content-center">
            <div class="mt-4 col-md-12">
                <div class="card">
                    <div class="card-header bg-success">
                        <h2 class="text-white">Calorific Values</h2>
                    </div>

                    <div class="card-body">
                        @livewire('search-widget')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" id="hiddenCid" name="cid" value="" />
                        <div class="mb-3">
                            <label for="applicablefor" class="col-form-label">Date:</label>
                            <input type="text" name="applicable_for" class="form-control" id="applicablefor" value="">
                        </div>
                        <div class="mb-3">
                            <label for="area" class="col-form-label">Area:</label>
                            <input type="text" name="area" class="form-control" id="area" value="">
                        </div>
                        <div class="mb-3">
                            <label for="clfvalue" class="col-form-label">Value:</label>
                            <input type="text" name="clf_value" class="form-control" id="clfvalue" value="">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="savedata"onclick="saveClfData()" >Save</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var editModal = document.getElementById('editModal');

        editModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            var button = event.relatedTarget
            var buttonId = button.getAttribute('data-cid');

            var datarow = document.getElementById('trow_'+buttonId);

            var modalInput1 = editModal.querySelector('#applicablefor');
            var modalInput2 = editModal.querySelector('#area');
            var modalInput3 = editModal.querySelector('#clfvalue');
            var modalInput4 = editModal.querySelector('#hiddenCid'); //Hidden Input

            modalInput1.value = datarow.cells[0].innerText;
            modalInput2.value = datarow.cells[1].innerText;
            modalInput3.value = datarow.cells[2].innerText;
            modalInput4.value = buttonId;
        });

        //window.addEventListener('DOMContentLoaded',function () {});

        function delClfData(btn) {
            var result = confirm("Are you sure you want to delete this record");

            if(result === true) {
                var buttonId = btn.getAttribute('data-cid');

                fetch("/delete-clf-data/"+buttonId, {
                    method: "POST",
                    body: JSON.stringify({
                        _token: document.querySelector('meta[name="csrf-token"]').content
                    }),
                    headers: {
                        "Content-type": "application/json; charset=UTF-8"
                    }
                }).then((result) => {
                    if (result.status != 200) {
                        alert("Sorry but record could not be deleted.");
                        throw new Error("Bad Server Response");
                    }
                    return result.json();
                }).then((response) => {
                    var datarow = document.getElementById('trow_'+buttonId);
                    datarow.style.display = 'none';
                    alert("Record deleted!");
                }).catch((error) => {
                    alert("Sorry but record could not be deleted.");
                    // @Todo - check error and handle it
                    console.log(error);
                });
            }

            return result;
        }
        function saveClfData() {
            // Get form data
            /*var data = new FormData();
            data.append("applicablefor", document.getElementById("applicablefor").value);
            data.append("area", document.getElementById("area").value);
            data.append("clf_value]", document.getElementById("clfvalue").value);
            data.append("_token", document.querySelector('meta[name="csrf-token"]').content); */

            var cid = document.getElementById('hiddenCid').value;

            //@Todo - Replace with Axios?
            //@Todo - add toast notification / or message saying saved

            // send post request
            fetch("/save-clf-data/"+cid, {
                method: "POST",
                body: JSON.stringify({
                    applicablefor: document.getElementById("applicablefor").value,
                    area: document.getElementById("area").value,
                    clf_value: document.getElementById("clfvalue").value,
                    _token: document.querySelector('meta[name="csrf-token"]').content
                }),
                headers: {
                    "Content-type": "application/json; charset=UTF-8"
                }
            }).then((result) => {
                if (result.status != 200) {
                    alert("Sorry but record could not be saved.");
                    throw new Error("Bad Server Response");
                }
                return result.json();
            }).then((response) => {
                var datarow = document.getElementById('trow_'+cid);
                datarow.cells[0].innerText = response.applicablefor;
                datarow.cells[1].innerText = response.area;
                datarow.cells[2].innerText = response.clf_value;
            }).catch((error) => {
                alert("Sorry but record could not be saved.");
                // @Todo - check error and handle it
                //console.log(error);
            });

            bootstrap.Modal.getInstance(editModal).hide(); //Hide modal
            return false;
        }

    </script>
@endsection
