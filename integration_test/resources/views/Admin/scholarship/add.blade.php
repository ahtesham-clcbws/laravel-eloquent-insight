<!-- resources/views/home.blade.php -->
@extends('layouts.master')

@section('title', 'Admin Panel')

@section('pagetype', 'Category')

@section('breadcrumb')
    <ol class="breadcrumb text-right">
        <li><a href="{{ route('course.category') }}">Classes</a></li>
        <li><a href="{{ route('course.category') }}">Scholorship</a></li>
    </ol>
@endsection
<style>
    .boxShadow {
        margin: 10px auto;
        background-color: #fff;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
</style>
@section('content')
<a href="{{ route('home.scholarshipList') }}"><button class="btn btn-xs btn-primary"> List</button></a>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default m-t-15">
                <div class="panel-heading">Add Scholorship</div>
                <div class="panel-body">
                    <div class="card alert">
                        <div class="card-body">
                            <form action="{{ route('home.savescholorship') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <p class="text-muted m-b-15 f-s-12">Name</p>
                                            <input type="text" name="examname" class="form-control input-focus"
                                                placeholder="Add Prefix">
                                        </div>

                                        <div class="form-group">
                                            <p class="text-muted m-b-15 f-s-12">Instruction</p>
                                            <textarea id="editor1" name="instruction"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <p class="text-muted m-b-15 f-s-12">Price</p>
                                            <input type="number" name="price" class="form-control input-focus"
                                                placeholder="Only Number i.e 5">
                                        </div>

                                        <div class="form-group">
                                            <p>Add Logo</p>
                                            <input type="file" id="fileInput" class="form-control input-focus"
                                                name="image">
                                            <img id="imagePreview" src="#" alt="Image Preview"
                                                style="display: none;width:200px">
                                        </div>

                                        <div class="form-group">
                                            <p>Available To</p>
                                            <input type="date" class="form-control input-focus" name="available_to">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">

                                        <div class="form-group">
                                            <p>Available From</p>
                                            <input type="date" class="form-control input-focus" name="available_from">
                                        </div>

                                        <div class="form-group">
                                            <p>Admit Card From</p>
                                            <input type="date" class="form-control input-focus" name="admit_Card_from">
                                        </div>
                                        <div class="form-group">
                                            <p>Result on</p>
                                            <input type="date" class="form-control input-focus" name="result_on">
                                        </div>

                                        <div class="form-group">
                                            <p>Maximum Number of Forms</p>
                                            <input type="number" class="form-control input-focus" name="maximum_forms">
                                        </div>

                                        <div class="form-group">
                                            <p>Exam On</p>
                                            <input type="date" class="form-control input-focus" name="exam_on">
                                        </div>

                                        <h5>Subject</h5>
                                        <hr>
                                        <table id="dataTable" class="table">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Total Ques.</th>
                                                    <th>Marks</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td><input type="text" class="form-control" name="name[]" required>
                                                    </td>
                                                    <td><input type="number" class="form-control" name="totalques[]"
                                                            required></td>
                                                    <td><input type="number" class="form-control" name="marks[]" required>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-xs btn-dark" onclick="addRow()">Add More
                                            Subject</button>
                                    </div>



                                    <script>
                                        function addRow() {
                                            var table = document.getElementById("dataTable");
                                            var row = table.insertRow();
                                            var cell1 = row.insertCell(0);
                                            var cell2 = row.insertCell(1);
                                            var cell3 = row.insertCell(2);
                                            var cell4 = row.insertCell(3);

                                            cell1.innerHTML = '<input type="text" class="form-control" name="name[]" required>';
                                            cell2.innerHTML = '<input type="number" class="form-control" name="totalques[]" required>';
                                            cell3.innerHTML = '<input type="number" class="form-control" name="marks[]" required>';
                                            cell4.innerHTML =
                                                '<button type="button" class="btn btn-xs btn-danger" onclick="removeRow(this)">Remove</button>';
                                        }

                                        function removeRow(button) {
                                            var row = button.parentNode.parentNode;
                                            row.parentNode.removeChild(row);
                                        }
                                    </script>

                                </div>
                                <input type="submit" class="btn btn-warning btn-flat m-b-10 m-l-5" value="Submit">
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-12">
            <div class="boxShadow">

            </div>

        </div>

    </div>
    <script>
        const fileInput = document.getElementById('fileInput');
        const imagePreview = document.getElementById('imagePreview');

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = '#';
                imagePreview.style.display = 'none';
            }
        });
    </script>

@endsection
