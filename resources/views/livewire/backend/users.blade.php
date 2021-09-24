<div>
    @section('page-title')
    Users
    @endsection

    <div class="container-fluid px-0">
        <div class="row mt-2 mb-3">
            <div class="col-6">
                @if ($user->role == 'sadmin')
                    <div class="me-lg-3">
                        <button class="btn btn-primary d-inline-flex align-items-center me-2" id="open-user-form">
                            <i class="fa fa-plus me-2"></i>
                            New User
                        </button>
                    </div>
                @endif 
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
                                <input type="text" class="form-control" id="exampleInputIconLeft" placeholder="Search" wire:model.debounce.500ms="search" aria-label="Search">
                            </div>
                        </div>
                        <div class="col-6 col-md-3  mb-2">
                            <div class="input-group">
                                <select class="form-control" wire:model="role">
                                    <option value="">- Select Role -</option>
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
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
                            @php
                            $i = $users->perPage() * ($users->currentPage() - 1)+1;
                            @endphp
                            @forelse ($users as $item)
                                @if ($item->role !== 'sadmin')
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
                                @endif
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
                {{$users->links()}}
            </div>
        </div>

    </div>

    <!-- Modals -->
    <div wire:ignore.self class="modal fade" id="newuser" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <form wire:submit.prevent="{{@$user->id?"update":"save"}}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <label for="role">Role<span class="text-danger">*</span></label>
                            <select class="form-control @error('user.role') is-invalid  @enderror"
                                wire:model="user.role">
                                <option value="">- Select -</option>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                            @error('user.role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="name">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('user.name') is-invalid  @enderror"
                                wire:model="user.name">
                            @error('user.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="username">Username<span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('user.username') is-invalid  @enderror"
                                wire:model="user.username">
                            @error('user.username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email">Email<span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('user.email') is-invalid  @enderror"
                                wire:model="user.email">
                            @error('user.email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password">Password<span class="text-danger">{{@$user->id?"":"*"}}</span></label>
                            <input type="password" class="form-control @error('password') is-invalid  @enderror"
                                wire:model="password">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="commission">Commission<span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('user.commission') is-invalid  @enderror"
                                wire:model="user.commission">
                            @error('user.commission') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="profit">Profit<span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('user.profit') is-invalid  @enderror"
                                wire:model="user.profit">
                            @error('user.profit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4 pt-1 px-2 border rounded">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="enabled" wire:model="user.enabled">
                                <label class="form-check-label" for="enabled">Enabled</label>
                            </div>
                            @error('user.enabled') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('backend._message')
    @push('script')
    <script>
        window.addEventListener('close-user-form', event => {
            $('#newuser').modal('hide');
        });

        window.addEventListener('open-user-form', event => {
            $('#newuser').modal('show');
        });

        $(document).on("click", '#open-user-form',() => {
            window.livewire.emit("user-new");
            $('#newuser').modal('show');
        });

        /**Edit */
        $(".user-edit-btn").click(function (e) {
            var id = $(this).attr("data-id");
            window.livewire.emit("user-edit",id);
        });
        /**Delete */
        $(".user-delete-btn").click(function (e) {
            e.preventDefault();

            var id = $(this).attr("data-id");
            $.confirm({
                title: '<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Delete Record!</span>',
                content: "Are you sure want to delete it?",
                buttons: {
                    confirm: {
                        text: 'Confirm',
                        btnClass: 'btn-red',
                        keys: ['enter', 'shift'],
                        action: function () {
                            window.livewire.emit("user-delete",id);
                            // $('#feed-delete-' + id).submit();
                        }
                    },
                    cancel: function () { },
                }
            });
        });
    </script>
    @endpush
</div>