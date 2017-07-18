@extends('layouts.default')
@section('content')
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">

    <div class="card mb-4">
        <div class="card-block">

            <a href="{{ URL::to('create/user') }}" class="btn btn-primary mb-4">Create User</a>
            <div id="no-more-tables" class="table-responsive">

                <table  class="table table-striped cf">
                    <thead class="cf">
                        <tr>
                            <th>S. no.</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                         <?php $i = 1 ?>
                         @foreach($user_data['data'] as  $user)
                         
                         
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td>{{ $user['name'] }}</td>                          
                            <td>
                                <a href="#" class="btn btn-sm btn-primary"><i class="fa fa-pencil-square" aria-hidden="true"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-info"><i class="fa fa-pencil-square" aria-hidden="true"></i>
                                    Edit permission</a>
                                <a href="#" class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>   
                        <?php $i++ ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
