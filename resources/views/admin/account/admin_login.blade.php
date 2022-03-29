@extends('admin_account')
@section('account_layout')

    <div class="main-page login-page">
        <h2 class="title1">Đăng nhập</h2>
        <div class="widget-shadow">
            <div class="login-body">

                    @if($errors->any())
                        <div class="alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                <form action="{{URL::to('/login-admin')}}" method="post">

                    {{ csrf_field() }}

                    <input type="email" class="user" name="ad_email" value="{{old('ad_email')}}" placeholder="Email" />
                    <input type="password" name="ad_password" class="lock" value="{{old('ad_password')}}"placeholder="Mật khẩu" />
                    <div class="forgot-grid">
                        <!-- <label class="checkbox"><input type="checkbox" name="checkbox" checked=""><i></i>Remember me</label> -->
                        <div class="forgot">
                            <a href="{{url('/forgot-pass')}}">Quên mật khẩu ?</a>
                        </div>
                        <div class="clearfix"> </div>
                    </div>
                    <input type="submit" name="login" value="Đăng nhập">
                    
                </form>

                <div class="row">
                    <p>Hoặc đăng nhập với</p>
                    <div class="col-sm-6 lg-fb">
                        <a href="{{url('/login-facebook')}}" class="social">
                            <i class="fa fa-facebook-f"></i>
                            <span>Facebook</span>
                        </a>
                    </div>
                    <div class="col-sm-6 lg-gg">
                        <a href="{{url('/login-google')}}" class="social">
                            <i class="fa fa-google-plus"></i>
                            <span>Google</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection