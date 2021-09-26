<div>
    @section('page-title')
    User Details
    @endsection

    <div class="container-fluid px-0">
        <div class="row mt-2 mb-3">
            <div class="col-6">
            </div>
            
            <div class="col-6 text-end">
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#fitlerBox"
                    aria-expanded="false" aria-controls="fitlerBox">
                    <i class="fa fa-filter me-2"></i> Filters
                </button>
            </div>
            <div wire:ignore.self class="col-12 mt-2 collapse" id="fitlerBox">
                <div class="card card-body">
                    <div class="row no-gutters">
                        <div class="col-12 col-md-3 mb-2">
                            From: <input type="date" class="form-control" name="date_from" wire:model="date_from" id="date_from" value="">
                        </div>
                        <div class="col-6 col-md-3  mb-2">
                            To: <input type="date" class="form-control" name="date_to" wire:model="date_to">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow mb-4">
            <div class="p-4 text-center">
                <div class="col-12">
                    <div class="text-center"><h4>User Details</h4></div>
                    <div class="row">
                        <div class="col-6 text-start">
                            <h6>Username: {{ $user->username }}</h6>
                            <h6>Name: {{ $user->name }}</h6>
                            <h6>User Id: {{ $user->id }}</h6>
                        </div>
                        
                        <div class="col-6 text-end">
                            @if ($this->date_to) 
                                <h6>From: {{ $this->date_from ?? '' }} </h6>
                                <h6>To: {{ $this->date_to ?? '' }}</h6>
                                <h6>Time: {{ Carbon\Carbon::now() }}</h6>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0 rounded">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0 rounded-start">#</th>
                                <th class="border-0">Category</th>
                                <th class="border-0">Bet Amount</th>
                                <th class="border-0">Win Amount</th>
                                <th class="border-0"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($items) > 0)
                                @php
                                    $i = $items->perPage() * ($items->currentPage() - 1)+1;
                                    $bet_amount = 0;
                                    $win_amount = 0;
                                @endphp
                                @foreach ($items as $item) 
                                <tr>
                                    <td>{{($i++)}}</td>
                                    <td>{{$item->category->name}}</td>
                                    <td>{{$item->bet_amount}}</td>
                                    <td>{{$item->win_amount}}</td>
                                    <td></td>
                                </tr>
                                @php
                                    $bet_amount+= $item->bet_amount;
                                    $win_amount+= $item->win_amount;
                                @endphp
                                @endforeach 
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><h6><b>Total: {{ $bet_amount }}</b></h6></td>
                                    <td><h6><b>Total: {{ $win_amount }}</b></h6></td>
                                    <td><h6><b>Balance: {{ $win_amount }}</b></h6></td>
                                </tr>
                            @else 
                                <tr>
                                    <td colspan="10" class="text-danger text-center py-5">
                                        No data found
                                    </td>
                                </tr>  
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                @if (!empty($items))
                    {{$items->links()}}
                @endif
            </div>
        </div>

    </div>

    @include('backend._message')
    @push('script')
        <script>
            $(document).ready(function(){
                var today = new Date();
                var dd = today.getDate();
                var mm = today.getMonth()+1; //January is 0!

                var yyyy = today.getFullYear();
                if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = mm+'/'+dd+'/'+yyyy;

                $('#date_from').attr('value', today);
            });
        </script>
    @endpush
</div>