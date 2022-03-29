<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">

            <input type="text" class="form-control" placeholder="Search by Date/Area/Value" wire:model="searchWord" />

            <table class="table table-bordered" style="margin: 10px 0 10px 0;">
                <tr>
                    <th>Date</th>
                    <th>Area</th>
                    <th>Value</th>
                    <th>Action</th>
                </tr>
                @foreach($calorificvalues as $calorificvalue)
                    <tr id="trow_{{ $calorificvalue->id }}">
                        <td>
                            {{ $calorificvalue->applicable_for }}
                        </td>
                        <td>
                            {{ $calorificvalue->area }}
                        </td>
                        <td>
                            {{ $calorificvalue->clf_value }}
                        </td>
                        <td>
                            <a href="#" class="editme" data-bs-toggle="modal" data-bs-target="#editModal" data-cid="{{ $calorificvalue->id }}">Edit</a> |
                            <a href="#" class="deleteme" data-cid="{{ $calorificvalue->id }}" onclick="delClfData(this)">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </table>
            {{ $calorificvalues->links() }}
        </div>
    </div>
</div>
