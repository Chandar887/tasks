<div>
    @section('page-title')
    User Details
    @endsection

    <div class="container-fluid px-0">
        <div class="row mt-2 mb-3">
            <div class="col-6">
                {{-- @if ($user->role == 'sadmin')
                    <div class="me-lg-3">
                        <button class="btn btn-primary d-inline-flex align-items-center me-2" id="open-user-form">
                            <i class="fa fa-plus me-2"></i>
                            New User
                        </button>
                    </div>
                @endif  --}}
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
                                wire:model.debounce.500ms="search" aria-label="Search">
                            </div>
                        </div>
                        <div class="col-6 col-md-3  mb-2">
                            <div class="input-group">
                                <select class="form-control" wire:model="category">
                                    <option value="">- Select Category -</option>
                                    @if (count($categories) > 0)
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    @endif    
                                </select>
                            </div>
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
                                <th class="border-0">Name</th>
                                <th class="border-0">Setting</th>
                                <th class="border-0">Status</th>
                                <th class="border-0 rounded-end">
                                    <div class="text-end">Action</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @if (!empty($items))
                                @php
                                $i = $items->perPage() * ($items->currentPage() - 1)+1;
                                @endphp
                                @forelse ($items as $item) 
                                @dd($item)
                                <tr>
                                    <td>{{($i++)}}</td>
                                    <td>{{$item->name}}</td>

                                    <td>
                                        Commission <span class="rounded text-danger p-1">{{$item->commission}}%</span><br />
                                        Profit <span class="rounded text-success p-1">{{$item->profit}}x</span>
                                    </td>

                                    <td>
                                        <small class="rounded p-1 text-light {{$item->enabled?"bg-success":"bg-danger"}}">
                                            {{$item->enabled?"Enabled":"Disabled"}}
                                        </small>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm" role="group">
                                            @if ($user->role == 'sadmin')
                                                <button type="button" class="btn btn-danger user-delete-btn"
                                                    data-id="{{$item->id}}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-info user-edit-btn"
                                                data-id="{{$item->id}}">
                                                <i class="fa fa-edit"></i></button>
                                            <a href="{{ route('user-details', $item->id) }}" class="btn btn-secondary">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-danger text-center py-5">
                                        No data found
                                    </td>
                                </tr>
                                @endforelse 
                            @endif --}}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{-- {{$users->links()}} --}}
            </div>
        </div>

    </div>

    @include('backend._message')
    @push('script')
    @endpush
</div>