@extends('layouts.app')

@section('title', 'Custom Receipt Generator')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Custom Receipt Generator</h1>
        <ol class="breadcrumb">
            <li><a href="{{action('HomeController@index')}}"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Custom Receipt</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Sales Transactions</h3>
                    </div>
                    <div class="box-body">
                        @if(count($transactions) > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Invoice No</th>
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transactions as $transaction)
                                            <tr>
                                                <td>{{ $transaction->invoice_no }}</td>
                                                <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('M d, Y H:i') }}</td>
                                                <td>
                                                    @if($transaction->contact)
                                                        {{ $transaction->contact->name }}
                                                    @else
                                                        Walk-in Customer
                                                    @endif
                                                </td>
                                                <td>{{ number_format($transaction->final_total, 2) }}</td>
                                                <td>
                                                    @if($transaction->payment_status == 'paid')
                                                        <span class="label label-success">Paid</span>
                                                    @elseif($transaction->payment_status == 'partial')
                                                        <span class="label label-warning">Partial</span>
                                                    @else
                                                        <span class="label label-danger">Unpaid</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{{ route('custom-receipt.generate', $transaction->id) }}" 
                                                           class="btn btn-primary btn-xs" 
                                                           target="_blank"
                                                           title="Generate PDF Receipt">
                                                            <i class="fa fa-file-pdf-o"></i> PDF
                                                        </a>
                                                        <a href="{{ route('custom-receipt.print', $transaction->id) }}" 
                                                           class="btn btn-success btn-xs" 
                                                           target="_blank"
                                                           title="Print Receipt">
                                                            <i class="fa fa-print"></i> Print
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="text-center">
                                {{ $transactions->links() }}
                            </div>
                        @else
                            <div class="text-center">
                                <h3>No sales transactions found.</h3>
                                <p>Start making sales to generate custom receipts.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('javascript')
<script>
$(document).ready(function() {
    // Add any JavaScript functionality here
});
</script>
@endsection
