@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="text-center">{{ __('Send Notification') }}</h3>
                        <hr />
                        <form action="{{ route('notification') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    @if (session('success'))
                                        <div class="alert alert-success" role="alert">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger" role="alert">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="">
                                            Address
                                        </label>
                                        
                                        <div id="address">
                                            <div class="chosentree"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="font-weight-bold" for="title">Title</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            name="title" placeholder="Enter Title">
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="font-weight-bold" for="title">Message</label>
                                        <textarea name="message" placeholder="Enter Message" class="form-control @error('message') is-invalid @enderror"></textarea>
                                        @error('message')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success btn-block">Send</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    {{-- tree select  --}}
    <script src="/backend/js/jquery.treeselect.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#examples');
        });
    </script>
    <script type='text/javascript'>
        jQuery(function() {
            $('div.chosentree').chosentree({
                width: 500,
                deepLoad: true,
                showtree: true,
                load: function(node, callback) {
                    setTimeout(function() {
                        callback(loadChildren(node, 0));
                    }, 1000);
                }
            });
        });
    </script>
    <script type='text/javascript'>
        var maxDepth = 3;
        var loadChildren = function(node, level) {
          var hasChildren = node.level < maxDepth;
          for (var i=0; i<8; i++) {
            var id = node.id + (i+1).toString();
            node.children.push({
              id:id,
              title:'Node ' + id,
              has_children:hasChildren,
              level: node.level + 1,
              children:[]
            });
            if (hasChildren && level < 2) {
              loadChildren(node.children[i], (level+1));
            }
          }
          return node;
        };
      </script>
@endsection
