@extends('layouts.main')

@section("content")
  <h1>Buy for 10$</h1>

  <Form id="billing-form" action="buy" method="POST">
    @csrf
    {{-- {{ csrf_field() }} --}}
    <div class="form-group">
      <label for="card number">Card Number:</label>
      <input type="text" class="form-control" id="card" placeholder="Enter card" data-stripe="number">
      <small id="cardHelp" class="form-text text-muted">We'll never share your card with anyone else.</small>
    </div>
    <div class="form-group">
      <label for="cvc">CVC:</label>
      <input type="text" class="form-control" id="cvc" placeholder="Enter cvc" data-stripe="cvc">
    </div>
    {{-- email --}}
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" placeholder="Enter email" data-stripe="email" name="email">
    </div>
    <div class="form-group">
      <label for="expiration date">Expiration Date:</label>
      {{-- a select dropdown contains month --}}
      <select  data-stripe="exp-month">
        <option value="1">January</option>
        <option value="2">February </option>
        <option value="3">March</option>
        <option value="4">April</option>
        <option value="5">May</option>
        <option value="6">June</option>
        <option value="7">July</option>
        <option value="8">August</option>
        <option value="9">September</option>
        <option value="10">October</option>
        <option value="11">November</option>
        <option value="12">December</option>
      </select>
        {{-- a select dropdown contains year --}}
       <select data-stripe="exp-year">
        <option value="2021">2021</option>
        <option value="2022">2022 </option>
        <option value="2023">2023</option>
        <option value="2024">2024</option>
        <option value="2025">2025</option>
        </select>
    </div>

    <input type="submit" class="btn btn-primary" value="Buy Now">
    <div class="payment-errors alert-danger"></div>
  </Form>


  @section('scripts')
    <script src="/js/billing.js"></script>
  @stop

@endsection