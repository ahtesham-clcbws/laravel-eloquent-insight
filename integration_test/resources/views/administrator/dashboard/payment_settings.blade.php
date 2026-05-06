@extends('administrator.layouts.master')

@section('title')
Student Payment
@endsection

@section('content')


<?php

use Illuminate\Support\Facades\Auth;

$admin = Auth::user();
?>
@if($admin->roles != 'admin')
{{ abort(403,'Permission Denied');}}
@endif
@section('content')
<div class="row py-2 pl-3 pr-3">
    <div class="container ">
        <div class="row">
            <div class="col-lg-11 col-md-11 col">
                <div class="panel panel-default m-t-15">
                    <div class="card-header">
                        <h5> {{$settings ? 'Update' : 'Add'}} Payment Key Secret:</h5>
                    </div>
                    <div class="panel-body">
                        <div class="card alert">
                            <div class="card-body">
                                <form action="{{ route('payment-settings.store') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 col">
                                            <div class="form-group">
                                                <p for="key_id">Key ID</p>
                                                <input class="form-control" type="text" id="key_id" name="key_id" value="{{ $settings->key_id ?? '' }}" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col">
                                            <div class="form-group">
                                                <p for="key_secret">Key Secret</p>
                                                <input class="form-control" type="text" id="key_secret" name="key_secret" value="{{ $settings->key_secret ?? '' }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-11 text-center">
                                        <input type="submit" class="btn btn-primary btn-flat m-b-10 m-l-5 w-50 mt-4" value="Submit">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection