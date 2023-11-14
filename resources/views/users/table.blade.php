<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="users-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Email Verified At</th>
                <th>Password</th>
                <th>Role Id</th>
                <th>Company Id</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>City</th>
                <th>Billing Id</th>
                <th>Remember Token</th>
                <th>Stripe Id</th>
                <th>Pm Type</th>
                <th>Pm Last Four</th>
                <th>Trial Ends At</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->email_verified_at }}</td>
                    <td>{{ $user->password }}</td>
                    <td>{{ $user->role_id }}</td>
                    <td>{{ $user->company_id }}</td>
                    <td>{{ $user->first_name }}</td>
                    <td>{{ $user->last_name }}</td>
                    <td>{{ $user->city }}</td>
                    <td>{{ $user->billing_id }}</td>
                    <td>{{ $user->remember_token }}</td>
                    <td>{{ $user->stripe_id }}</td>
                    <td>{{ $user->pm_type }}</td>
                    <td>{{ $user->pm_last_four }}</td>
                    <td>{{ $user->trial_ends_at }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['users.destroy', $user->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('users.show', [$user->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('users.edit', [$user->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-edit"></i>
                            </a>
                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $users])
        </div>
    </div>
</div>
