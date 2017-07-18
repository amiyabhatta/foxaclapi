@extends('layouts.default')
@section('content')

<div class="col-sm-12 col-md-6">
    <div class="card mb-4">
        <div class="card-block">           

            <form class="form-horizontal" action="" method="post">
                <fieldset>
                    <!-- Name input-->
                    <div class="form-group">
                        <!-- <label class="col-12 control-label no-padding" for="name">Name</label> -->

                        <div class="col-12 no-padding">
                            <input id="fullname" name="fullname" type="text" placeholder="Full Name " class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <!-- <label class="col-12 control-label no-padding" for="name">Name</label> -->

                        <div class="col-12 no-padding">
                            <input id="username" name="username" type="text" placeholder="User Name" class="form-control">
                        </div>
                    </div>

                    <!-- Email input-->
                    <div class="form-group">
                        <!-- <label class="col-12 control-label no-padding" for="email">Your E-mail</label> -->

                        <div class="col-12 no-padding">
                            <input id="email" name="email" type="text" placeholder=" Email" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <!-- <label class="col-12 control-label no-padding" for="email">Your E-mail</label> -->

                        <div class="col-12 no-padding">
                            <input id="password" name="password" type="text" placeholder="Password" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <!-- <label class="col-12 control-label no-padding" for="email">Your E-mail</label> -->

                        <div class="col-12 no-padding">
                            <input id="confirmpassword" name="confirmpassword" type="text" placeholder="Confirm Password" class="form-control">
                        </div>
                    </div>


                    <div class="form-group">
                        <h6>Choose server</h1>
                            <div class="row">
                                <div class="col-md-3 float-left">
                                    <input id="box1" type="checkbox" />
                                    <label for="box1">Checkbox 1</label>
                                </div>
                                <div class="col-md-3 float-left">
                                    <input id="box2" type="checkbox" />
                                    <label for="box2">Checkbox 1</label>
                                </div>
                                <div class="col-md-3 float-left">
                                    <input id="box3" type="checkbox" />
                                    <label for="box3">Checkbox 1</label>
                                </div>
                                <div class="col-md-3 float-left">
                                    <input id="box3" type="checkbox" />
                                    <label for="box3">Checkbox 1</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 float-left">
                                    <input id="box1" type="checkbox" />
                                    <label for="box1">Checkbox 1</label>
                                </div>
                                <div class="col-md-3 float-left">
                                    <input id="box2" type="checkbox" />
                                    <label for="box2">Checkbox 1</label>
                                </div>
                                <div class="col-md-3 float-left">
                                    <input id="box3" type="checkbox" />
                                    <label for="box3">Checkbox 1</label>
                                </div>
                                <div class="col-md-3 float-left">
                                    <input id="box3" type="checkbox" />
                                    <label for="box3">Checkbox 1</label>
                                </div>
                            </div>
                    </div>

                    <!-- Form actions -->
                    <div class="form-group">
                        <div class="col-12 widget-right no-padding">

                            <button type="reset" class="btn btn-info btn-md float-right">Reset</button>
                            <button type="submit" class="btn mr-2 btn-primary btn-md float-right">Submit</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

</div>
@stop
