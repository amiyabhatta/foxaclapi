@extends('layouts.login')
@section('content')

<div class="row">
    <div class="login_back text-center">
        <a href="index.html"><img  class="mb-4" src="{{asset('assets/images/white_logo_2.png')}}"></a>
        <form class="form-horizontal" action="" method="post">
            <fieldset>
                <!-- Name input-->
                <div class="form-group text-left">
                    <!-- <label class="col-12 control-label no-padding" for="name">Name</label> -->

                    <div class="col-12 no-padding position_rel">
                        <i class="fa fa-user"></i>
                        <input id="email" name="email" type="text" placeholder="Your Email" class="form-control pl-4">
                    </div>
                </div>

                <!-- Email input-->
                <div class="form-group text-left">
                    <!-- <label class="col-12 control-label no-padding" for="email">Your Password</label> -->

                    <div class="col-12 no-padding position_rel">
                        <i class="fa fa-lock"></i>
                        <input id="password" name="email" type="password" placeholder="Your password" class="form-control pl-4">
                    </div>
                </div>



                <!-- Form actions -->
                <div class="form-group">
                    <div class="col-12 widget-right float-left no-padding mb-2">
                        <button type="submit" class="btn btn-danger w-100 float-right">Login</button>
                    </div>
                </div>

                <div class="form-group text-center forgot_password">
                    <button type="button" data-toggle="modal" data-target="#exampleModal" data-whatever="@getbootstrap">Forgot Password?</button>
                </div>

            </fieldset>
        </form>
    </div>
</div>

@stop
