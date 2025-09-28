{{-- resources/views/mortgage/calculate.blade.php --}}
@extends('layouts.app')

@section('content')

<div class="container">
<div class="card"> 
    <h5 class="card-header text-primary"> Loan Calculator</h5>
    <div class="card-body">
    <form method="post" action="{{ url('/calculate-loanable-amount') }}" id="mortgageForm">
        @csrf

                <div class="input-group mb-3">
                    <span class="input-group-text">TSHS</span>
                    <input type="number" name="basic_salary" placeholder="Basic Salary" required class="form-control" aria-label="Basic Salary">
                </div>

                <div id="allowanceFields">
                    <!-- Dynamic allowance field -->
                    <div class="input-group mb-3 allowance-field">
                        <input type="number" name="allowances[]" placeholder="Allowance" required class="form-control">
                        <button type="button" class="btn btn-outline-secondary remove-field">Remove</button>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <button type="button" id="addAllowance" class="btn btn-outline-secondary">Add Allowance</button>
                </div>

                <div class="input-group mb-3">
                    <input type="number" name="take_home" placeholder="Take Home Pay" required class="form-control" aria-label="Take Home Pay">
                </div>

                <div class="input-group mb-3">
                    <input type="number" name="number_of_months" placeholder="Repayment Period (Months)" required class="form-control" aria-label="Repayment Period">
                </div>

                <button type="submit" class="btn btn-primary btn-sm">Calculate</button>
                <button type="reset" class="btn btn-warning btn-sm">Reset</button>
            </div>
        </div>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#addAllowance').click(function() {
            var newField = `
                <div class="input-group mb-3 allowance-field">
                    <input type="number" name="allowances[]" placeholder="Allowance" required class="form-control">
                    <button type="button" class="btn btn-outline-secondary remove-field">Remove</button>
                </div>`;
            $('#allowanceFields').append(newField);
        });

        $(document).on('click', '.remove-field', function() {
            $(this).closest('.input-group').remove();
        });
    });
    </script>

@endsection
