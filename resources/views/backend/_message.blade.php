<div>
    @if (session()->has('success'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 99;">
        <div class="toast align-items-center text-white bg-success show" data-bs-autohide="true">
            <div class="d-flex">
                <div class="toast-body">
                    <span><i class="fas fa-check-circle"></i></span>
                    <span class="px-2">{{ session('success') }}</span>
                </div>
                {{-- <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button> --}}
            </div>
        </div>
    </div>
    @endif
    @if (session()->has('error'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 99;">
        <div class="toast align-items-center text-white bg-danger show" data-bs-autohide="true">
            <div class="d-flex">
                <div class="toast-body">
                    <span><i class="fas fa-exclamation-circle"></i></span>
                    <span class="px-2">{{ session('error') }}</span>
                </div>
                {{-- <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button> --}}
            </div>
        </div>
    </div>
    
    @endif
    @if (session()->has('warning'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 99;">
        <div class="toast align-items-center text-white bg-warning show" data-bs-autohide="true">
            <div class="d-flex">
                <div class="toast-body">
                    <span><i class="fas fa-exclamation-triangle"></i></span>
                    <span class="px-2">{{ session('warning') }}</span>
                </div>
                {{-- <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button> --}}
            </div>
        </div>
    </div>
    @endif
</div>