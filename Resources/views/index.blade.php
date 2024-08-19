@extends('chjumpseat::layouts.frontend')

@section('title', 'CHJumpSeat')

@section('content')
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <div style="float:right;">
            <a class="btn btn-outline-info pull-right btn-lg"
               style="margin-top: -10px;margin-bottom: 5px"
               href="{{ route('chjumpseat.create') }}">New Jumpseat</a>
          </div>
          <h2>Previous Jumpseats</h2>
          @include('flash::message')
          <div class="table-responsive">
            <table class="table table-hover table-striped">
              <thead>
              <tr>
                <th>Created On</th>
                <th>Type</th>
                <th>Airport</th>
                <th>Request Reason</th>
                <th class="text-center">Status</th>
              </tr>
              </thead>
              <tbody>

              @foreach($requests as $req)
                <tr>
                  <td>
                    {{$req->created_at}}
                  </td>
                  <td>
                    @php
                      $color = 'badge-info';
                      if($req->type === 0) {
                          $color = 'badge-warning';
                          $text = "Request";
                      } elseif ($req->type === 1) {
                          $color = 'badge-info';
                          $text = "Self";
                      } else {
                          $color = 'badge-secondary';
                          $text = "Unknown";
                      }
                    @endphp
                    <div class="badge {{ $color }}">{{ $text }}</div>
                  </td>
                  <td>
                    {{$req->airport->id}} - {{$req->airport->name}}
                  </td>
                  <td>
                    {{$req->request_reason}}
                  </td>
                  <td class="text-center">
                    @php
                      $color = 'badge-info';
                      if($req->status === 0) {
                          $color = 'badge-warning';
                          $text = "Pending";
                      } elseif ($req->status === 1) {
                          $color = 'badge-success';
                          $text = "Accepted";
                      } elseif ($req->status === 2) {
                          $color = 'badge-danger';
                          $text = "Rejected";
                      }
                    @endphp
                    <div class="badge {{ $color }}">{{ $text }}</div>
                  </td>
                </tr>
              @endforeach

              </tbody>
            </table>
          </div>

        </div>
      </div>
      <div class="row">
        <div class="col-12 text-center">
          {{ $requests->withQueryString()->links('pagination.default') }}
        </div>
      </div>
    </div>
  </div>

@endsection
