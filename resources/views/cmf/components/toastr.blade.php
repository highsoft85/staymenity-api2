@if(\Illuminate\Support\Facades\Session::has('toastr::notifications'))
    {!! \App\Services\Toastr\Facades\Toastr::render() !!}
@else
    <script>
        window.toastrOptions = @json(config('toastr.options'));
    </script>
@endif
