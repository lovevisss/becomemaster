<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $user->name }}</p>
</div>

<!-- Email Field -->
<div class="col-sm-12">
    {!! Form::label('email', 'Email:') !!}
    <p>{{ $user->email }}</p>
</div>

<!-- Email Verified At Field -->
<div class="col-sm-12">
    {!! Form::label('email_verified_at', 'Email Verified At:') !!}
    <p>{{ $user->email_verified_at }}</p>
</div>

<!-- Password Field -->
<div class="col-sm-12">
    {!! Form::label('password', 'Password:') !!}
    <p>{{ $user->password }}</p>
</div>

<!-- Role Id Field -->
<div class="col-sm-12">
    {!! Form::label('role_id', 'Role Id:') !!}
    <p>{{ $user->role_id }}</p>
</div>

<!-- Company Id Field -->
<div class="col-sm-12">
    {!! Form::label('company_id', 'Company Id:') !!}
    <p>{{ $user->company_id }}</p>
</div>

<!-- First Name Field -->
<div class="col-sm-12">
    {!! Form::label('first_name', 'First Name:') !!}
    <p>{{ $user->first_name }}</p>
</div>

<!-- Last Name Field -->
<div class="col-sm-12">
    {!! Form::label('last_name', 'Last Name:') !!}
    <p>{{ $user->last_name }}</p>
</div>

<!-- City Field -->
<div class="col-sm-12">
    {!! Form::label('city', 'City:') !!}
    <p>{{ $user->city }}</p>
</div>

<!-- Billing Id Field -->
<div class="col-sm-12">
    {!! Form::label('billing_id', 'Billing Id:') !!}
    <p>{{ $user->billing_id }}</p>
</div>

<!-- Remember Token Field -->
<div class="col-sm-12">
    {!! Form::label('remember_token', 'Remember Token:') !!}
    <p>{{ $user->remember_token }}</p>
</div>

<!-- Stripe Id Field -->
<div class="col-sm-12">
    {!! Form::label('stripe_id', 'Stripe Id:') !!}
    <p>{{ $user->stripe_id }}</p>
</div>

<!-- Pm Type Field -->
<div class="col-sm-12">
    {!! Form::label('pm_type', 'Pm Type:') !!}
    <p>{{ $user->pm_type }}</p>
</div>

<!-- Pm Last Four Field -->
<div class="col-sm-12">
    {!! Form::label('pm_last_four', 'Pm Last Four:') !!}
    <p>{{ $user->pm_last_four }}</p>
</div>

<!-- Trial Ends At Field -->
<div class="col-sm-12">
    {!! Form::label('trial_ends_at', 'Trial Ends At:') !!}
    <p>{{ $user->trial_ends_at }}</p>
</div>

