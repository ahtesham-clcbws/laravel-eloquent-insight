@extends('layouts.website')

@section('title', 'Home Page')


@section('content')
    <style>
        .boxShadow {
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .total-price {
            font-size: 17px;
            margin-bottom: 20px;
        }

        .coupon-code {
            margin-bottom: 20px;
        }

        .coupon-code input {
            padding: 10px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 13px;
        }

        .coupon-code button {
            padding: 9px 38px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            font-size: 11px;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 10px
        }

        .payment-button {
            text-align: center;
        }

        .payment-button button {
            padding: 9px 38px;
            border: none;
            border-radius: 4px;
            background-color: #28a745;
            color: #fff;
            font-size: 11px;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>

    <body class="conact-page">
        <section>

        </section>
        <div class="faq comm-p-t-b">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default m-t-15">
                            <div class="panel-heading">Scholorship Info</div>
                            <div class="panel-body">
                                <div class="card alert">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <div class="boxShadow">
                                                    @if ($scholarshipForm)
                                                        <?php $formData = json_decode($scholarshipForm); ?>
                                                        {{-- <p>Name: {{ $formData->name }}</p>
                                                    <p>Father's Name: {{ $formData->fathersname }}</p>
                                                    <p>Date of Birth: {{ $formData->dob }}</p> --}}
                                                        <!-- Output other fields as needed -->
                                                    @else
                                                        <p>No scholarship form data found for this user.</p>
                                                    @endif
                                                    <form action="{{ route('home.scholorship_insert') }}" method="POST"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="formPadding">
                                                            @if (session('error'))
                                                                <div class="alert alert-danger">
                                                                    {{ session('error') }}
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <div class="contact__msg">Thank you.</div>

                                                                <div class="form-group">
                                                                    <input type="text" name="name"
                                                                        value="{{ $formData->name }}"
                                                                        placeholder="Applicant’s Name" class="form-control"
                                                                        required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <input type="text" name="fathers_name"
                                                                        value="{{ $formData->fathersname }}"
                                                                        placeholder="Father’s/ Mother’s Name"
                                                                        class="form-control" required>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <input type="date" name="dob"
                                                                            id="dob"value="{{ $formData->dob }}"
                                                                            placeholder="DOB" class="form-control" required>
                                                                    </div>

                                                                    <div class="col-lg-6">
                                                                        <input type="radio" name="gender" id="gender"
                                                                            value="male" required
                                                                            style="width: 15px;height:15px">
                                                                        Male &nbsp;
                                                                        <input type="radio" name="gender" id="gender"
                                                                            value="female" required
                                                                            style="width: 15px;height:15px">
                                                                        FeMale
                                                                    </div>
                                                                </div>


                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label for="">Image</label>
                                                                            <input type="file" name="image"
                                                                                id="image" class="form-control"
                                                                                required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-6">
                                                                        <label for="">Sign</label>
                                                                        <input type="file" name="sign" id="sign"
                                                                            class="form-control" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="">Person with Disability</label>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-lg-6">
                                                                            <input type="radio" name="disability"
                                                                                id="disability" value="yes" required
                                                                                style="width: 15px;height:15px"> Yes &nbsp;
                                                                        </div>

                                                                        <div class="col-lg-6">
                                                                            <input type="radio" name="disability"
                                                                                id="disability" value="no" required
                                                                                style="width: 15px;height:15px"> No
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <input type="address" name="address" id="address"
                                                                        value="{{ $formData->address }}"
                                                                        placeholder="Address" class="form-control" required>
                                                                </div>

                                                                <div class="form-group">
                                                                    <select class="form-control" name="city">
                                                                        <option value="">Select City</option>
                                                                        <option value="Patna">Patna</option>
                                                                    </select>
                                                                </div>

                                                                <div class="form-group">
                                                                    <Select class="form-control" name="qualification">
                                                                        <option value="">Qualifications</option>
                                                                        <option value="Intermediate">Intermediate</option>
                                                                    </Select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-lg-4">
                                                                            <div class="form-group">
                                                                                <label for="">You want to
                                                                                    Participate in
                                                                                    Exams</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-4">
                                                                            <select class="form-control"
                                                                                name="participate_exam">
                                                                                <option value="">Select Exam</option>
                                                                                <option>CAREER PREP SCHOLARSHIP EXAM
                                                                                </option>
                                                                                <option>Edusmart Academic Fellowship
                                                                                </option>
                                                                                <option>SUPER-60 SCHOLARSHIP PROGRAM
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="">Choice of test centre</label>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-lg-4">
                                                                            <select class="form-control" name="center1">
                                                                                <option value="">Centre</option>
                                                                                <option value="Patna">Patna</option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-lg-4">
                                                                            <select class="form-control" name="center2">
                                                                                <option value="">Centre</option>
                                                                                <option value="Kolkata">Kolkata</option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-lg-4">
                                                                            <select class="form-control" name="center3">
                                                                                <option value="">Centre</option>
                                                                                <option value="Begusarai">Begusarai
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="">Did you participate in any Govt/
                                                                        Competitive Exam(s)</label>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-lg-4">
                                                                            <select class="form-control" name="exam1">
                                                                                <option value="Exam 1">Exam 1</option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-lg-4">
                                                                            <select class="form-control" name="exam2">
                                                                                <option value="Exam 1">Exam 1</option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-lg-4">
                                                                            <select class="form-control" name="exam3">
                                                                                <option value="exam 1">Exam 1</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <div class="form-group">
                                                                    <label for="" style="font-weight: bold">If
                                                                        Previously Apply For This
                                                                        Scholarship</label>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-lg-6">
                                                                            <div class="form-group">
                                                                                <select id=""
                                                                                    class="form-control" name="year">
                                                                                    <option value="">Year</option>
                                                                                    <option value="2019">2019</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-6">
                                                                            <div class="form-group">
                                                                                <input type="text" name="roll_no"
                                                                                    class="form-control"
                                                                                    placeholder="Roll No">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-lg-6">
                                                                            <div class="form-group">
                                                                                <select class="form-control"
                                                                                    name="family_income">
                                                                                    <option value="">Family Income
                                                                                    </option>
                                                                                    <option value="1500000">15000000
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-6">
                                                                            <div class="form-group">
                                                                                <select class="form-control"
                                                                                    name="occupation">
                                                                                    <option value="">Guardian
                                                                                        Occupation
                                                                                    </option>
                                                                                    <option value="Govt">Govt</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <input type="checkbox" style="width: 20px;height:20px"
                                                                        name="privacy_policy" required> &nbsp;
                                                                    I agree for career without barrier’s Terms & Conditions
                                                                </div>
                                                                <div class="form-group">
                                                                    <input type="submit" class="btn btn-dark"
                                                                        name="submit" value="Submit">
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                <div class="boxShadow">
                                                    <div class="total-price">
                                                        <h4>Coupon Code</h4>
                                                        <hr>

                                                    </div>
                                                    <div class="coupon-code">
                                                        <input type="text" placeholder="Enter coupon code">
                                                        <button>Apply</button>
                                                    </div>

                                                </div>

                                                <div class="boxShadow">
                                                    <div class="total-price">
                                                        <h4>Payment</h4>
                                                        <hr>
                                                        Total Price: &nbsp; &nbsp;<span class="fa fa-rupee"></span> 5000
                                                        <!-- You can dynamically generate this value using JavaScript -->
                                                    </div>
                                                    {{-- <div class="coupon-code">
                                                        <input type="text" class="form-control" placeholder="Enter coupon code">

                                                    </div> --}}
                                                    <div class="payment-button">

                                                            <button>
                                                                <form action="{{ route('razorpay.payment.store') }}"
                                                                method="POST">
                                                                @csrf
                                                                <script src="https://checkout.razorpay.com/v1/checkout.js" data-key="{{ env('RAZORPAY_KEY') }}" data-amount="10000"
                                                                    data-buttontext="Proceed to Payment" data-name="GeekyAnts official" data-description="Razorpay payment"
                                                                    data-image="/images/logo-icon.png" data-prefill.name={{ Auth::user()->name }} data-prefill.email={{ Auth::user()->email }}
                                                                    data-theme.color="#f26b3c"></script>
                                                            </form>
                                                            </button>



                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input type="submit" class="btn btn-warning btn-flat m-b-10 m-l-5" value="Submit">
                                </form>


                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    @endsection
