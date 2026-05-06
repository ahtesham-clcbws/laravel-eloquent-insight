@extends('layouts.master')

@section('title', 'Admin Panel')

@section('pagetype', 'Company Details')

@section('breadcrumb')
    <ol class="breadcrumb text-right">
        <li><a href="{{ route('course.category') }}">Classes</a></li>
        <li><a href="{{ route('course.category') }}">Company</a></li>
    </ol>
@endsection

@section('content')
<form action="{{ route('home.saveCompany') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Software Url</label>
                    <input type="text" class="form-control" name="softwareurl" value="{{ $company->softwareurl ?? '' }}" placeholder="Software Url" value="#">
                </div>

            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>School Name</label>
                    <input type="text" class="form-control" name="companyname" value="{{ $company->companyname ?? '' }}"  placeholder="Company Name" value="#">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Short Name</label>
                    <input type="text" class="form-control" name="shortname" value="{{ $company->shortname ?? '' }}" placeholder="Short Name" value="#">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>CIN</label>
                    <input type="text" class="form-control" name="cin" value="{{ $company->cin ?? '' }}" placeholder="CIN">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Logo</label>
                    <input type="file" class="form-control" value="{{ $company->logo ?? '' }}" name="logo">
                </div>
            </div>


            <div class="col-md-3">
                <div class="form-group">
                    <label>PAN</label>
                    <input type="text" class="form-control" value="{{ $company->pan ?? '' }}" name="pan" placeholder="PAN">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>TAN</label>
                    <input type="text" class="form-control" value="{{ $company->tan ?? '' }}" name="tan" placeholder="TAN">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>GST No.</label>
                    <input type="text" class="form-control" value="{{ $company->gst ?? '' }}" name="gst" placeholder="GST No.">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>School Category</label>
                    <select class="form-control" name="companycategory">
                        <option>Select</option>
                        <option {{ $company->companycategory == 'Company Category' ? 'selected' : '' }}>Company Category</option>
                        <option {{ $company->companycategory == 'Limited by Shares' ? 'selected' : '' }}>Limited by Shares</option>
                        <option {{ $company->companycategory == 'Limited by Guarantee' ? 'selected' : '' }}>Limited by Guarantee</option>
                        <option {{ $company->companycategory == 'Unlimited Company' ? 'selected' : '' }}>Unlimited Company</option>
                    </select>
                </div>
            </div>


            <div class="col-md-3">
                <div class="form-group">
                    <label>School Class</label>
                    <select class="form-control" name="companyclass">
                        <option>Select</option>
                        <option {{ $company->companyclass == 'Company Class' ? 'selected' : '' }}>Company Class</option>
                        <option {{ $company->companyclass == 'Private Limited Company' ? 'selected' : '' }}>Private Limited Company</option>
                        <option {{ $company->companyclass == 'One Person Private Limited Company' ? 'selected' : '' }}>One Person Private Limited Company</option>
                    </select>
                </div>
            </div>


            <div class="col-md-3">
                <div class="form-group">
                    <label>Authorized Capital</label>
                    <input type="text" class="form-control" value="{{ $company->authorizedcapital ?? '' }}" name="authorizedcapital"
                        placeholder="Authorized Capital">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Paid Up Capital</label>
                    <input type="text" class="form-control" value="{{ $company->paidupcapital ?? '' }}" name="paidupcapital"
                        placeholder="Paid Up Capital">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Share Nominal Value</label>
                    <input type="text" class="form-control" value="{{ $company->sharenominalvalue ?? '' }}" name="sharenominalvalue"
                        placeholder="Share Nominal Value">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>State of Registration</label>
                    <select class="form-control" name="stateofregistration">
                        <option>State</option>
                        <option {{ $company->stateofregistration == 'Andhra Pradesh' ? 'selected' : '' }}>Andhra Pradesh</option>
                        <option {{ $company->stateofregistration == 'Arunachal Pradesh' ? 'selected' : '' }}>Arunachal Pradesh</option>
                        <option {{ $company->stateofregistration == 'Assam' ? 'selected' : '' }}>Assam</option>
                        <option {{ $company->stateofregistration == 'Bihar' ? 'selected' : '' }}>Bihar</option>
                        <option {{ $company->stateofregistration == 'Chhattisgarh' ? 'selected' : '' }}>Chhattisgarh</option>
                        <option {{ $company->stateofregistration == 'Goa' ? 'selected' : '' }}>Goa</option>
                        <option {{ $company->stateofregistration == 'Gujarat' ? 'selected' : '' }}>Gujarat</option>
                        <option {{ $company->stateofregistration == 'Haryana' ? 'selected' : '' }}>Haryana</option>
                        <option {{ $company->stateofregistration == 'Himachal Pradesh' ? 'selected' : '' }}>Himachal Pradesh</option>
                        <option {{ $company->stateofregistration == 'Jharkhand' ? 'selected' : '' }}>Jharkhand</option>
                        <option {{ $company->stateofregistration == 'Karnataka' ? 'selected' : '' }}>Karnataka</option>
                        <option {{ $company->stateofregistration == 'Kerala' ? 'selected' : '' }}>Kerala</option>
                        <option {{ $company->stateofregistration == 'Madhya Pradesh' ? 'selected' : '' }}>Madhya Pradesh</option>
                        <option {{ $company->stateofregistration == 'Maharashtra' ? 'selected' : '' }}>Maharashtra</option>
                        <option {{ $company->stateofregistration == 'Manipur' ? 'selected' : '' }}>Manipur</option>
                        <option {{ $company->stateofregistration == 'Meghalaya' ? 'selected' : '' }}>Meghalaya</option>
                        <option {{ $company->stateofregistration == 'Mizoram' ? 'selected' : '' }}>Mizoram</option>
                        <option {{ $company->stateofregistration == 'Nagaland' ? 'selected' : '' }}>Nagaland</option>
                        <option {{ $company->stateofregistration == 'Odisha' ? 'selected' : '' }}>Odisha</option>
                        <option {{ $company->stateofregistration == 'Punjab' ? 'selected' : '' }}>Punjab</option>
                        <option {{ $company->stateofregistration == 'Rajasthan' ? 'selected' : '' }}>Rajasthan</option>
                        <option {{ $company->stateofregistration == 'Sikkim' ? 'selected' : '' }}>Sikkim</option>
                        <option {{ $company->stateofregistration == 'Tamil Nadu' ? 'selected' : '' }}>Tamil Nadu</option>
                        <option {{ $company->stateofregistration == 'Telangana' ? 'selected' : '' }}>Telangana</option>
                        <option {{ $company->stateofregistration == 'Tripura' ? 'selected' : '' }}>Tripura</option>
                        <option {{ $company->stateofregistration == 'Uttar Pradesh' ? 'selected' : '' }}>Uttar Pradesh</option>
                        <option {{ $company->stateofregistration == 'Uttarakhand' ? 'selected' : '' }}>Uttarakhand</option>
                        <option {{ $company->stateofregistration == 'West Bengal' ? 'selected' : '' }}>West Bengal</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Incorporation Date</label>
                    <input type="date" class="form-control" value="{{ $company->incorporationdate ?? '' }}" name="incorporationdate"
                        placeholder="Share Nominal Value">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" value="{{ $company->email ?? '' }}" name="email" placeholder="Email">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Phone</label>
                    <input type="number" class="form-control" value="{{ $company->phone ?? '' }}" name="phone" placeholder="Phone">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Landline No.</label>
                    <input type="text" class="form-control" value="{{ $company->landlineno ?? '' }}" name="landlineno"
                        placeholder="Landline No.">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>WhatsApp No.</label>
                    <input type="text" class="form-control" value="{{ $company->whatsappno ?? '' }}" name="whatsappno"
                        placeholder="Landline No.">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>City</label>
                    <input type="text" class="form-control" name="city" value="{{ $company->city ?? '' }}" placeholder="City">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>State</label>
                    <select class="form-control" name="state">
                        <option>State</option>
                        <option {{ $company->state == 'Andhra Pradesh' ? 'selected' : '' }}>Andhra Pradesh</option>
                        <option {{ $company->state == 'Arunachal Pradesh' ? 'selected' : '' }}>Arunachal Pradesh</option>
                        <option {{ $company->state == 'Assam' ? 'selected' : '' }}>Assam</option>
                        <option {{ $company->state == 'Bihar' ? 'selected' : '' }}>Bihar</option>
                        <option {{ $company->state == 'Chhattisgarh' ? 'selected' : '' }}>Chhattisgarh</option>
                        <option {{ $company->state == 'Goa' ? 'selected' : '' }}>Goa</option>
                        <option {{ $company->state == 'Gujarat' ? 'selected' : '' }}>Gujarat</option>
                        <option {{ $company->state == 'Haryana' ? 'selected' : '' }}>Haryana</option>
                        <option {{ $company->state == 'Himachal Pradesh' ? 'selected' : '' }}>Himachal Pradesh</option>
                        <option {{ $company->state == 'Jharkhand' ? 'selected' : '' }}>Jharkhand</option>
                        <option {{ $company->state == 'Karnataka' ? 'selected' : '' }}>Karnataka</option>
                        <option {{ $company->state == 'Kerala' ? 'selected' : '' }}>Kerala</option>
                        <option {{ $company->state == 'Madhya Pradesh' ? 'selected' : '' }}>Madhya Pradesh</option>
                        <option {{ $company->state == 'Maharashtra' ? 'selected' : '' }}>Maharashtra</option>
                        <option {{ $company->state == 'Manipur' ? 'selected' : '' }}>Manipur</option>
                        <option {{ $company->state == 'Meghalaya' ? 'selected' : '' }}>Meghalaya</option>
                        <option {{ $company->state == 'Mizoram' ? 'selected' : '' }}>Mizoram</option>
                        <option {{ $company->state == 'Nagaland' ? 'selected' : '' }}>Nagaland</option>
                        <option {{ $company->state == 'Odisha' ? 'selected' : '' }}>Odisha</option>
                        <option {{ $company->state == 'Punjab' ? 'selected' : '' }}>Punjab</option>
                        <option {{ $company->state == 'Rajasthan' ? 'selected' : '' }}>Rajasthan</option>
                        <option {{ $company->state == 'Sikkim' ? 'selected' : '' }}>Sikkim</option>
                        <option {{ $company->state == 'Tamil Nadu' ? 'selected' : '' }}>Tamil Nadu</option>
                        <option {{ $company->state == 'Telangana' ? 'selected' : '' }}>Telangana</option>
                        <option {{ $company->state == 'Tripura' ? 'selected' : '' }}>Tripura</option>
                        <option {{ $company->state == 'Uttar Pradesh' ? 'selected' : '' }}>Uttar Pradesh</option>
                        <option {{ $company->state == 'Uttarakhand' ? 'selected' : '' }}>Uttarakhand</option>
                        <option {{ $company->state == 'West Bengal' ? 'selected' : '' }}>West Bengal</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Pincode</label>
                    <input type="number" class="form-control" value="{{ $company->pincode ?? '' }}" name="pincode" placeholder="Pincode">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Razorpay Marchent ID</label>
                    <input type="number" class="form-control" value="{{ $company->razorpay_marchent_id ?? '' }}" name="razorpay_marchent_id" placeholder="Pincode">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Razorpay Marchent Key</label>
                    <input type="number" class="form-control" value="{{ $company->razorpay_marchent_key ?? '' }}" name="razorpay_marchent_key" placeholder="Razorpay Marchent Key">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>SMS API Key</label>
                    <input type="number" class="form-control" value="{{ $company->sms_api_key ?? '' }}" name="sms_api_key" placeholder="SMS API Key">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>SMS API Link</label>
                    <input type="number" class="form-control" value="{{ $company->sms_api_link ?? '' }}" name="sms_api_link" placeholder="SMS API Link">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Address</label>
                    <textarea class="form-control" name="address">{{ $company->address ?? '' }}</textarea>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>About</label>
                    <textarea class="form-control" name="about">{{ $company->about ?? '' }}</textarea>
                </div>
            </div>

        </div>
        <input type="submit" class="btn btn-sm btn-danger btn-sm" style="width: 100px" value="Update">
    </form>

    </div>
    </div>

    <!-- /.content -->
    </div>
@endsection
