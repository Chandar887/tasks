<div>
    @section('page-title')
    Report - User
    @endsection

    <div class="container-fluid px-0">
        <div class="row mt-2 mb-3">
            <div class="col-6">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn {{$groupBy=='Day'?'btn-primary':'btn-secondary'}}" wire:click="ChangeOrder('Day')"
                        wire:loading.disabled="true">Day</button>
                    <button type="button" class="btn {{$groupBy=='Week'?'btn-primary':'btn-secondary'}}" wire:click="ChangeOrder('Week')"
                        wire:loading.disabled="true">Week</button>
                    <button type="button" class="btn {{$groupBy=='Month'?'btn-primary':'btn-secondary'}}" wire:click="ChangeOrder('Month')"
                        wire:loading.disabled="true">Month</button>
                    <button type="button" class="btn {{$groupBy=='Year'?'btn-primary':'btn-secondary'}}" wire:click="ChangeOrder('Year')"
                        wire:loading.disabled="true">Year</button>
                </div>
            </div>
            <div class="col-6 text-end">
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#fitlerBox"
                    aria-expanded="false" aria-controls="fitlerBox">
                    <i class="fa fa-filter me-2"></i> Filters
                </button>
            </div>
            <div class="col-12 mt-2 collapse" id="fitlerBox">
                <div class="card card-body">
                    <div class="row no-gutters">
                        <div class="col-12 col-md-3 mb-2">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span>
                                <input type="text" class="form-control" id="exampleInputIconLeft" placeholder="Search"
                                    aria-label="Search">
                            </div>
                        </div>
                        
                        <div class="col-6 col-md-3 mb-2">
                            <button class="btn btn-secondary">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0 rounded">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0 rounded-start">#</th>
                                <th class="border-0">User</th>
                                {{-- <th class="border-0">Category</th> --}}
                                <th class="border-0">Bet Amount</th>
                                <th class="border-0">Win Amount</th>
                                <th class="border-0">{{$groupBy}}</th>
                                {{-- <th class="border-0 rounded-end">
                                    <div class="text-end">Action</div>
                                </th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $i = $bets->perPage() * ($bets->currentPage() - 1)+1;
                            @endphp
                            @forelse ($bets as $item)
                            <tr>
                                <td>{{($i++)}}</td>
                                <td>{{@$item->user->name}}<Br><small class="text-muted">{{@$item->user->role}}</small>
                                </td>
                                <td>
                                    ₹{{@$item->bet_amount}}
                                </td>
                                <td>
                                    ₹{{@$item->win_amount}}
                                </td>
                                <td>
                                    {{@$item->date}}
                                </td>
                                {{-- <td>
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y h:i A')}}
                                </td> --}}
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-danger text-center py-5">
                                    No data found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{$bets->links()}}
            </div>
        </div>

    </div>
    @include('backend._message')
</div>