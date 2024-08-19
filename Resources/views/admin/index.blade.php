@extends('chjumpseat::layouts.admin')

@section('title', 'CHJumpSeat')

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <h2>Jumpseat Requests</h2>
        @include('flash::message')
        <div class="table-responsive">
          <table class="table table-hover table-striped">
            <thead>
            <tr>
              <th>User</th>
              <th>Created On</th>
              <th>Type</th>
              <th>Airport</th>
              <th>Request Reason</th>
              <th class="text-center">Status</th>
              <th class="text-right">Actions</th>
            </tr>
            </thead>
            <tbody>

            @foreach($requests as $req)
              <tr>
                <td>{{$req->user->name}}</td>
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
                <td class="text-right">
                  @if($req->status == 0)
                  <a href="#" class="btn btn-success" role="button" onclick="event.preventDefault();
                                    document.getElementById('accept{{ $req->id }}').submit();"><i class="fa fa-check"></i></a>
                  <form id="accept{{ $req->id }}" method="POST" action="{{ route('admin.chjumpseat.status', [$req->id]) }}" accept-charset="UTF-8" hidden>
                    {{ csrf_field() }}
                    <input name="flag" type="hidden" value="status">
                    <input name="status" type="hidden" value="1">
                  </form>

                  <a href="#" class="btn btn-danger" role="button" onclick="event.preventDefault();
                                    document.getElementById('reject{{ $req->id }}').submit();"><i class="fa fa-times"></i></a>
                  <form id="reject{{ $req->id }}" method="POST" action="{{ route('admin.chjumpseat.status', [$req->id]) }}" accept-charset="UTF-8" hidden>
                    {{ csrf_field() }}
                    <input name="flag" type="hidden" value="status">
                    <input name="status" type="hidden" value="2">
                  </form>
                  @endif
                </td>
              </tr>
            @endforeach

            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12 text-center">
      {{ $requests->withQueryString()->links('admin.pagination.default') }}
    </div>
  </div>
@endsection
