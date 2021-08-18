@extends('asimshazad::layouts.auth')

@section('title', 'Seotools')
@section('child-content')
    <div class="row mb-3">
        <div class="col-md">
            <h2 class="mb-0 text-dark">@yield('title')</h2>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            {!! $html->table() !!}
        </div>
    </div>

    <div class="modal fade" id="filterModalCenter" tabindex="-1" role="dialog" aria-labelledby="filterModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalCenterTitle">Advanced Filter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="model">Model</label>
                        <input type="text" class="form-control filterInput" name="model" id="model" value="{{ old('filter.model') }}">
                    </div>
                    <div class="form-group">
                        <label for="model_id">ID</label>
                        <input type="text" class="form-control filterInput" name="model_id" id="model_id" value="{{ old('filter.model_id') }}">
                    </div>
                    <div class="form-group">
                        <label for="created_at_range">Date</label>
                        <input type="text" class="form-control filterInput" name="created_at_range" id="created_at_range" value="{{ old('filter.created_at_range') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="filterBtn"><i class="fal fa-search mr-2"></i>Filter</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
{!! $html->scripts() !!}
@include('asimshazad::layouts.datatable_scripts',compact('dtid','export_url'))
<script type="text/javascript">
(function(window, $) {
    $('#created_at_range').daterangepicker({
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        autoUpdateInput: false,
        locale: {
          format: 'YYYY-MM-DD'
        }
    });
    $('#created_at_range').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
    });
    $('#created_at_range').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
})(window, jQuery);
</script>
@endpush
