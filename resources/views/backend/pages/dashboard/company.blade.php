<div class="main-content-inner mt-3 company">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12 mb-3 text-md-start">
                    <h3 class="text-center">{{$pageTitle}}</h3>
                </div>
                @if( $companies )
                    @foreach( $companies as $data )
                        <div class="col-md-6 col-lg-4 col-sm-6 col-12 mb-3">
                            <a href="{{url( 'admin/'.$industryURL.'/company/'._en( $data->id ) )}}" class="btn btn-theme w-100">
                                <img src="{{url('public/backend/assets/images/icon/company/company.png')}}" class="px-1"/>
                                {{$data->name}}
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-12 mb-3">
                        <div class="card text-center p-4">
                            No Company availble
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
