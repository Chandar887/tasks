<div>
    @section('page-title')
    Bets
    @endsection

    @push('style')
    <style>
        .number-card {
            transition: all linear 300ms;
        }

        .number-card:hover .number-card-button {
            transition: all linear 300ms;
            background-color: #10B981;
        }

        .number-card:hover .number-card-button span {
            transition: all linear 300ms;
            display: none;
        }

        .number-card:hover .number-card-button:before {
            transition: all linear 300ms;
            content: "+"
        }

        .blink {
            color: red;
            font-weight: bolder;
            animation: blinker 800ms linear infinite;
        }

        @keyframes blinker {
            50% {
                opacity: 0.3;
            }
        }
    </style>
    @endpush
    
    @if (empty($category))
        <div class="card border-0 shadow mb-4 py-8 text-center">
            <div class="text-danger small font-weight-bold">No Category Found</div>
        </div>
    @else
    
    <div class="nav-wrapper position-relative">
        <ul class="nav nav-pills nav-fill">
            @foreach ($categories as $cat)
            <li class="nav-item mx-1 mb-1 p-0">
                <button class="nav-link px-2 py-1 text-capitalize {{ $cat->id == $category->id ? 'active bg-primary text-light' : ''}}"
                    wire:click="changeCategory({{$cat->id}})">{{$cat->name}}</button>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="card border-0 shadow mb-4">
        <div class="card-head">
            <div class="row no-gutters mt-2 ">
                <div class="col-md-3 col-6">
                    <div class="text-center shadow border overflow-hidden">
                        <div class="bg-secondary h4 m-0 bet-timer">
                            @if(@$result->result == null)
                                @if ($current_time < $start_time)
                                    @php $msg = 'Bet Starts After' @endphp
                                    {{ $start_timer }}
                                @elseif ($current_time > $start_time && $current_time < $end_time) 
                                    @php $msg = 'Bet Expired After' @endphp
                                    {{ $stop_timer }}
                                @else 
                                    @php $msg = 'Bet Expired' @endphp
                                    {{ "00:00:00" }}
                                @endif 
                            @else 
                                @php $msg = 'Bet Expired' 
                                @endphp
                                {{ "00:00:00" }} 
                            @endif 
                        </div>
                        <div class="bg-light text-dark font-weight-bold">{{ $msg }}</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="text-center shadow border overflow-hidden">
                        <div class="bg-secondary h4 m-0">₹ {{@$result->bet_amount??"-"}}</div>
                        <div class="bg-light text-dark font-weight-bold">Total Bet</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="text-center shadow border overflow-hidden">
                        <div class="bg-secondary h4 m-0">₹ {{@$result->win_amount??"-"}}</div>
                        <div class="bg-light text-dark font-weight-bold">Total Payable</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="text-center shadow border overflow-hidden">
                        @if (empty($result->result))
                            @if ($user->role == 'sadmin')
                                <button class="bg-dark text-light h4 m-0 border-0 w-100  declare-result-button">Declare
                                    Result</button>
                            @else
                                <button class="bg-dark text-light h4 m-0 border-0 w-100">Result Pending</button>
                            @endif         
                        @else
                        <div class="bg-secondary h4 m-0">{{@$result->result}}</div>
                        @endif
                        <div class="bg-light text-dark font-weight-bold">Result</div>
                    </div>
                </div>
            </div>

        </div>
        <div class="card-body p-1" 
        wire:poll
        >
            @for ($n=1,$i = 1; $i <= 10; $i++) <div class="row no-gutters justify-content-center text-center">
                @for ($j = 1; $j <= 10; $j++,$n++) 
                @php $tota_bet=$bets[$n]??'-'; @endphp 
                <div
                    class="col-2 col-md-1 px-0 m-1 card shadow overflow-hidden hover number-card"
                    style="cursor: default">
                    <button
                        class="font-weight-bold h4 m-0 border-0 number-card-button {{$tota_bet!=0?"bg-warning":""}} @if(!empty(@$result->result) && @$result->result==$n) blink @endif"
                        title="Add Bet" data-number="{{$n}}" data-bet-result="{{ @$result->result }}">
                        <span>{{$n}}</span>
                    </button>
                    <div class="bg-info text-light small" title="Bet">
                        {{$tota_bet}}
                    </div>
        </div>
        @endfor
    </div>
    @endfor
    <div class="row no-gutters justify-content-center text-center">
    @for ($i=0; $i <= 9; $i++) 
    
        @php
            $n="$i$i$i"; 
            $tota_bet=$bets[$n]??'-'; 
        @endphp 
        <div
            class="col-2 col-md-1 px-0 m-1 card shadow overflow-hidden hover number-card"
            style="cursor: default">
            <button
                class="font-weight-bold h4 m-0 border-0 number-card-button {{$tota_bet!=0?"bg-warning":""}} @if(!empty(@$result->result) && @$result->result==$n) blink @endif"
                title="Add Bet" data-number="{{$n}}">
                <span>{{$n}}</span>
            </button>
            <div class="bg-danger text-light small" title="Bet">
                {{$tota_bet}}
            </div>
        </div>
        @endfor
    </div>
    
    <!-- Modals -->
    {{-- <div wire:ignore.self class="modal" id="addbet" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog justify-content-center">
            <form wire:submit.prevent="saveBet">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Bet</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if($errors->any())
                        {!! implode('', $errors->all('<div class="text-danger mb-2">:message</div>')) !!}
                        @endif
                        <div class="mb-2">
                            <label for="name">Number<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" min="1" max="100"
                                    class="form-control @error('bet.number') is-invalid  @enderror"
                                    wire:model="bet.number"  id="bet_number">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="name">Bet<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('bet.bet_amount') is-invalid  @enderror"
                                    wire:model="bet.bet_amount" id="bet_amount" min="{{@$category->min_bet}}"
                                    max="{{@$category->max_bet}}">
                            </div>
                        </div>
                        <button type="button" class="add-new-field">Add New</button>
                    <div class="add-new-number"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary rounded-0">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div> --}}

    <div wire:ignore class="modal" id="addbet" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog justify-content-center">
            <form id="save-bet" data-category-id="{{ @$category->id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Bet</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if($errors->any())
                        {!! implode('', $errors->all('<div class="text-danger mb-2">:message</div>')) !!}
                        @endif
                        <div class="row mb-2">
                            <div class="col-6">
                                <label for="name">Number<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" min="1" max="100"
                                        class="form-control bet_number_err @error('bet.number') is-invalid  @enderror"
                                        wire:model="bet.number" name="bet_number[]" id="bet_number" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <label for="name">Bet<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="bet_amount[]" class="form-control bet_amount_focus bet_amount_err @error('bet.bet_amount') is-invalid  @enderror bet_amount"
                                        wire:model="bet.bet_amount" min="{{@$category->min_bet}}"
                                        max="{{@$category->max_bet}}" data-category-min-bet="{{@$category->min_bet}}" data-category-max-bet="{{@$category->max_bet}}" required>
                                </div>
                            </div>
                        </div>
                    <div class="add-new-field"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary rounded-0">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div wire:ignore.self class="modal" id="showbets" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog justify-content-center">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bet Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-sm table-nowrap mb-0 rounded">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0">User</th>
                                    <th class="border-0">Bet</th>
                                    <th class="border-0">Time</th>
                                    <th class="border-0 rounded-end">
                                        <div class="text-end">Action</div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($betsData) && count($betsData))

                                @foreach ($betsData as $item)
                                <tr>
                                    <td>{{$item->user->id}}# {{$item->user->name}}</td>
                                    <td>
                                        {{$item->bet_amount}}
                                    </td>
                                    <td>
                                        <small class="">
                                            {{$item->created_at->format('h:i A');}}
                                        </small>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-sm btn-danger bet-delete-btn samll"
                                                data-id="{{$item->id}}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="10">
                                        <div class="text-center text-danger small font-weight-bold">No Data Exisit.</div>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal" id="{{ $user->role == 'sadmin' ? 'declareResult' : '' }}" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog justify-content-center">
            @if ($user->role == 'sadmin') 
                <form wire:submit.prevent="declareResult">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Declare Result</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if($errors->any())
                            {!! implode('', $errors->all('<div class="text-danger mb-2">:message</div>')) !!}
                            @endif
                            <div class="mb-2">
                                <label for="result_value">Number<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-control @error('result_value') is-invalid  @enderror"
                                        wire:model="result_value" id="result_value">
                                        <option value=""> - Select - </option>
                                        @for ($i = 1; $i <= 100; $i++) <option value="{{$i}}">{{$i}}</option>
                                            @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary rounded-0">Save</button>
                        </div>
                    </div>
                </form>
            @endif    
        </div>
    </div>

    <div wire:ignore class="modal" id="showErrors" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog justify-content-center">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Bet Status</h4>
                    <button type="button" class="btn-close close-showError-modal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                  <h5 class="text-center show-error"></h5>
                </div>
            </div>
        </div>
    </div>
    
    @endif
    @push('script')
    <script>
        var clicks = 0, timer = null;
        var not_started = "{{ $bet_status == App\Enums\BetStatus::NOT_STARTED }}";
        var expired = "{{ $bet_status == App\Enums\BetStatus::EXPIRED }}";

        /*Add Bets Dialog*/
        $("#addbet").on('shown.bs.modal', function(){
            $(this).find('.bet_amount_focus').focus();
        });
        window.addEventListener('close-bet-form', event => {
            $('#addbet').modal('hide');
        });

        window.addEventListener('open-bet-form', event => {
            $('#addbet').modal('show');
        });

       /*Declare Result*/
        $('.close-showError-modal').click(function(){
            $('#showErrors').modal('hide');
        });
        
        /*Show Bet Details Dialog*/
        $('.number-card-button').on("click", function() {
            $('.add-new-field').html('');
            let ele = $(this);
            let data_number=ele.attr("data-number");
            $("#bet_number").val(data_number);
            clicks++;

            if (clicks === 1) {
                timer = setTimeout(function () {
                    clicks = 0;
                    window.livewire.emit("bet-new",data_number);
                    var bet_result = ele.attr("data-bet-result");

                    if (bet_result == '') {
                        if (not_started) {
                            $('#showErrors').modal('show');
                            $('.show-error').html('Bet not started yet.');
                        } else if (expired) {
                            $('#showErrors').modal('show');
                            $('.show-error').html('Bet expired.');
                        } else {
                            $('#addbet').modal('show');
                        }
                    } else {
                        $('#showErrors').modal('show');
                        $('.show-error').html('Bet expired.');
                    }
                },300);
            } else {
                clicks = 0;
                clearTimeout(timer);
                window.livewire.emit("bets-detail",data_number);
                $('#showbets').modal('show');
            }
        });

        $(document).on("keydown",".bet_amount:last",function(e){
            var keyCode = e.keyCode || e.which; 
            var min_bet = $(this).attr("data-category-min-bet");
            var max_bet = $(this).attr("data-category-min-bet");
            
            if (keyCode == 9) {
                var html = '<div class="row mb-2 added-fileds">';
                html += '<div class="col-5">';
                html += '<label for="name">Number<span class="text-danger">*</span></label>';
                html += '<input type="number" min="1" max="100" name="bet_number[]" class="form-control bet_number_err" value="" required>';
                html += '</div>';
                html += '<div class="col-5">';
                html += '<label for="name">Bet<span class="text-danger">*</span></label>';
                html += '<input type="number" name="bet_amount[]" class="form-control bet_amount bet_amount_err" min="'+min_bet+'" max="'+max_bet+'" required>';
                html += '</div><div class="col-2"><button type="button" class="remove-field text-danger" style="margin-top:35px;"><i class="fa fa-trash"></i></button></div></div>';
                $('.add-new-field').append(html);
            }
        });

        $(document).on("click",".remove-field",function(e){
            $(this).closest('.added-fileds').remove();
        });

        window.addEventListener('close-bets-details', event => {
            $('#showbets').modal('hide');
        });

        window.addEventListener('open-bets-details', event => {
            $('#showbets').modal('show');
        });

        /*Declare Result*/

        $('.declare-result-button').on("click", function() {
            let ele = $(this);
            $('#declareResult').modal('show');
        });

        window.addEventListener('close-declare-result', event => {
            $('#declareResult').modal('hide');
        });

        window.addEventListener('open-declare-result', event => {
            $('#declareResult').modal('show');
        });
        

        /**Delete */
        // $(".bet-delete-btn").click(function (e) {
        $(document).on("click",".bet-delete-btn", function (e) {
            e.preventDefault();

            var id = $(this).attr("data-id");
            $.confirm({
                title: '<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Delete Record!</span>',
                content: "Are you sure want to delete bet?",
                buttons: {
                    confirm: {
                        text: 'Confirm',
                        btnClass: 'btn-red',
                        keys: ['enter', 'shift'],
                        action: function () {
                            window.livewire.emit("bet-delete",id);
                            // $('#feed-delete-' + id).submit();
                        }
                    },
                    cancel: function () { },
                }
            });
        });

        /**Save Bet */
        $(document).on("submit","#save-bet", function (e) {
            e.preventDefault();
            var category_id = $(this).attr("data-category-id");
            var bet_number_arr = [];
            $('input[name="bet_number[]"]').each(function() {
                bet_number_arr.push($(this).val());
            });

            var bet_amount_arr = [];
            $('input[name="bet_amount[]"]').each(function() {
                bet_amount_arr.push($(this).val());
            });

            var result;
            if (bet_number_arr.length > 0 && bet_amount_arr.length > 0 && bet_number_arr.length == bet_amount_arr.length) {
                result = bet_amount_arr.reduce(function(result, field, index) {
                result[bet_number_arr[index]] = field;
                return result;
                }, {})

                window.livewire.emit("bet-save",result,category_id);
            }
        });
    </script>
    @endpush
</div>