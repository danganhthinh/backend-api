@if(Session::has('message'))
    <div class="alert-noti alert bg-success alert-dismissible mb-2 fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        {{Session::get('message')}}
    </div>
@endif
@if(Session::has('success'))
    <div class="alert-noti alert bg-success alert-dismissible mb-2 fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        {{Session::get('success')}}
    </div>
@endif
@if(Session::has('error'))
    <div class="alert-noti alert bg-danger alert-dismissible mb-2 fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        {{Session::get('error')}}
    </div>
@endif
