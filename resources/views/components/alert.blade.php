@if(session('success'))
    <!-- ========== УВЕДОМЛЕНИЕ ========== -->
    <div class="toast">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="toast error">
        {{ session('error') }}
    </div>
@endif

