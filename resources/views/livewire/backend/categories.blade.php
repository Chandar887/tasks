<div>
    @section('page-title')
    Categories
    @endsection

    <div class="container-fluid px-0">
        <div class="row mt-2 mb-3">
            <div class="col-6">
                @if ($user->role == 'sadmin')
                    <div class="me-lg-3">
                        <button class="btn btn-primary d-inline-flex align-items-center me-2" id="open-category-form">
                            <i class="fa fa-plus me-2"></i>
                            New Category
                        </button>
                    </div>
                @endif 
            </div>
            <div class="col-6 px-0 text-end">

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
                                <th class="border-0">Timing</th>
                                <th class="border-0">Bet Limit</th>
                                <th class="border-0">Status</th>
                                <th class="border-0 rounded-end">
                                    <div class="text-end">Action</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $i = $categories->perPage() * ($categories->currentPage() - 1)+1;
                            @endphp
                            @forelse ($categories as $cat)
                            <tr>
                                <td>{{($i++)}}</td>
                                <td>{{$cat->name}}</td>
                                <td>
                                    From <span class="rounded text-danger p-1">{{$cat->start_time}}</span>
                                    To <span class="rounded text-success p-1">{{$cat->end_time}}</span>
                                </td>
                                <td>
                                    From <span class="rounded text-danger p-1">{{$cat->min_bet}}</span>
                                    To <span class="rounded text-success p-1">{{$cat->max_bet}}</span>
                                </td>
                                <td>
                                    <small class="rounded p-1 text-light {{$cat->enabled?"bg-success":"bg-danger"}}">
                                        {{$cat->enabled?"Enabled":"Disabled"}}
                                    </small>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        @if ($user->role == 'sadmin')
                                            <button type="button" class="btn btn-danger category-delete-btn"
                                                data-id="{{$cat->id}}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <button type="button" class="btn btn-info category-edit-btn"
                                                data-id="{{$cat->id}}">
                                                <i class="fa fa-edit"></i></button>
                                        @endif
                                        <button type="button" class="btn btn-secondary">
                                            <i class="fa fa-eye"></i>
                                        </button>
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
                            <tr>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{$categories->links()}}
            </div>
        </div>

    </div>

    <!-- Modals -->
    <div wire:ignore.self class="modal fade" id="newcategory" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            {{-- @livewire('backend.categories-form') --}}
            <form wire:submit.prevent="{{@$category->id?"update":"save"}}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">New Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <label for="name">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('category.name') is-invalid  @enderror"
                                wire:model="category.name">
                            @error('category.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <div class="row no-gutters">
                                <div class="col-6">
                                    <label for="start_time">Bet Start Time<span class="text-danger">*</span></label>
                                    <input type="time"
                                        class="form-control @error('category.start_time') is-invalid  @enderror"
                                        wire:model="category.start_time">
                                    @error('category.start_time') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label for="end_time">Bet End Time<span class="text-danger">*</span></label>
                                    <input type="time"
                                        class="form-control @error('category.end_time') is-invalid  @enderror"
                                        wire:model="category.end_time">
                                    @error('category.end_time') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

                        </div>

                        <div class="mb-4">
                            <div class="row no-gutters">
                                <div class="col-6">
                                    <label for="min_bet">Min Bet<span class="text-danger">*</span></label>
                                    <input type="number"
                                        class="form-control @error('category.min_bet') is-invalid  @enderror"
                                        wire:model="category.min_bet">
                                    @error('category.min_bet') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label for="max_bet">Max Bet<span class="text-danger">*</span></label>
                                    <input type="number"
                                        class="form-control @error('category.max_bet') is-invalid  @enderror"
                                        wire:model="category.max_bet">
                                    @error('category.max_bet') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="mb-4 pt-1 px-2 border rounded">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="enabled"
                                    wire:model="category.enabled">
                                <label class="form-check-label" for="enabled">Enabled</label>
                            </div>
                            @error('category.enabled') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
        window.addEventListener('close-category-form', event => {
            $('#newcategory').modal('hide');
        });

        window.addEventListener('open-category-form', event => {
            $('#newcategory').modal('show');
        });

        $(document).on("click", '#open-category-form',() => {
            window.livewire.emit("category-new");
            $('#newcategory').modal('show');
        });

        /**Edit */
        $(".category-edit-btn").click(function (e) {
            var id = $(this).attr("data-id");
            window.livewire.emit("category-edit",id);
        });
        /**Delete */
        $(".category-delete-btn").click(function (e) {
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
                            window.livewire.emit("category-delete",id);
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