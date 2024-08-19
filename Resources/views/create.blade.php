@extends('app')
@section('content')
  <div class="card">

    <div class="card-body">
      <h1>New Jumpseat</h1>
      @include('flash::message')
      <form method="POST" action="{{route('chjumpseat.store')}}">
        {{csrf_field()}}
        <div class="form-group">
          <div class="form-check">
            <input class="" type="radio" name="type" value="0" id="flexRadioDefault1">
            <label class="form-check-label" for="flexRadioDefault1">
              Make Jumpseat Request
            </label>
          </div>
          <div class="form-check">
            <input class="" type="radio" name="type" id="flexRadioDefault2" value="1" checked>
            <label class="form-check-label" for="flexRadioDefault2">
              Pay for Immediate Jumpseat ({{ $price->money->formatForHumans() }})
            </label>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            Airport
            <div class="form-group">
              <select class="custom-select airport_search" name="airport_id"></select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            Reason
            <div class="form-group">
              <input class="form-control" name="request_reason"/>
            </div>
          </div>
        </div>
        <button class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    $(document).ready(function () {
      $("select.airport_search").select2({
        ajax: {
          url: '{{ Config::get("app.url") }}/api/airports/search',
          data: function (params) {
            const hubs_only = $(this).hasClass('hubs_only') ? 1 : 0;
            return {
              search: params.term,
              hubs: hubs_only,
              page: params.page || 1,
              orderBy: 'id',
              sortedBy: 'asc'
            }
          },
          processResults: function (data, params) {
            if (!data.data) { return [] }
            const results = data.data.map(apt => {
              return {
                id: apt.id,
                text: apt.description,
              }
            })

            const pagination = {
              more: data.meta.next_page !== null,
            }

            return {
              results,
              pagination,
            };
          },
          cache: true,
          dataType: 'json',
          delay: 250,
          minimumInputLength: 2,
        },
        width: 'resolve',
        placeholder: 'Type to search',
      });
    });
  </script>
@endsection
