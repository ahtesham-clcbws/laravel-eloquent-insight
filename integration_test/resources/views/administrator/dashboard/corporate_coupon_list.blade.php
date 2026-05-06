@extends('administrator.layouts.master')
@section('title')
Corporate List
@endsection
@section('content')
<style>
  .pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
  }

  .pagination>.page-item>.page-link {
    color: #000000;
    border: 1px solid #3c4248;
    margin: 0 5px;
    font-size: 15px;
  }

  .pagination>.page-item>.page-link:hover {
    background-color: #f8f9fa;
  }

  .pagination>.page-item.active>.page-link {
    background-color: #007bff;
    border-color: #007bff;
  }

  .pagination>.page-item.disabled>.page-link {
    color: #6c757d;
    pointer-events: none;
    cursor: default;
  }

  .boxShadow {
    margin: 10px auto;
    background-color: #fff;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

  .pagination .page-item .page-link span {
    font-size: 1rem;
    /* Adjust the font size as needed */
  }

  .appliedCount{
    cursor: pointer;
  }
</style>

<h3 style="padding-top: 10px;padding-left: 10px;">
      Discount Voucher Details: {{$corporate->name}}
    </h3>
<div class="row">
  <div class="col-lg-11 col-md-11 col" style="margin-left: auto;margin-right:auto">
   
    <div class="container boxShadow d-flex">
      <div class="row headerGridBox" style="width: 100%;">
        <div class="col-md-2 col-2">
          <label for="couponCode">Coupon Code:</label>
          <select class="form-select text-center" id="couponCode">
            <option value="">--Select Coupon Code--</option>
            @foreach($coupons->whereNotNull('prefix')->pluck('prefix','prefix') as $key=>$value)
            <option value="{{$value}}">{{$value}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2 col-2">
          <label for="couponValue">Code Value:</label>
          <div class="couponValue" style="text-align: center;padding: 6px;border-radius: .2rem;    height: 2.3rem;border: 1px solid #d6d6d6;">
            {{$codeValue}}
          </div>
        </div>
        <div class="col-md-2 col-2">
          <label for="prefixtxt">Prefix:</label>
          <div class="prefixtxt" style="text-align: center;padding: 6px;border-radius: .2rem;    height: 2.3rem;border: 1px solid #d6d6d6;">
            {{$prefix}}
          </div>
        </div>
        <div class="col-md-2 col-2">
          <label for="totalcount">Total Count:</label>
          <div class="totalcount" style="text-align: center;padding: 6px;border-radius: .2rem;    height: 2.3rem;border: 1px solid #d6d6d6;">
            {{$counts}}
          </div>
        </div>
        <div class="col-md-2 col-2">
          <label for="issuedCount">Issued Coupon:</label>
          <div class="issuedCount" style="text-align: center;padding: 6px;border-radius: .2rem; height: 2.3rem;border: 1px solid #d6d6d6;">
            {{$issuedCount}}
          </div>
        </div>
        <div class="col-md-2 col-2">
          <label for="appliedCount">Applied Coupon:</label>
          <div class="appliedCount" style="text-align: center;padding: 6px;border-radius: .2rem; height: 2.3rem;border: 1px solid #d6d6d6;">
            {{$appliedCount}}
          </div>
        </div>

      </div>
    </div>
    <table class="table table-striped datatablecl" style="width:100%">
      <thead class="thead-light">
        <tr>
          <th style="width:12%"> <input type="checkbox" id="selectAll">
            <label for="selectAll">Select All</label>
          </th>
          <th>Sr.No.</th>
          <th>Prefix</th>
          <th>Name</th>
          <th>Coupon Code</th>
          <th >Status</th>
        </tr>
      </thead>
      <tbody class="table-striped table-striped-coupon">

        @foreach ($coupons as $coupon)
        <tr style="<?= $coupon->is_applied ? 'background-color: #2180614d;' : '' ?>">
          <td> <input type="checkbox" class="selectSingle"></td>
          <td style="font-size: 13px">{{ $loop->index+1 }}</td>
          <td style="font-size: 13px">{{ $coupon->prefix }}</td>
          <td style="font-size: 13px">{{ $coupon->name }}</td>
          <td style="font-size: 13px">{{ $coupon->couponcode }}</td>
          <td style="font-size: 13px">{{ $coupon->status ? ($coupon->is_applied ? 'Applied' : 'Active') : 'Inactive' }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>

  </div>
</div>
</div>
<script>
  $(document).ready(function() {

    var areRowsHidden = true; // Flag to track if rows are hidden
    
    $('.appliedCount').click(function() {
        $('.table-striped.datatablecl tbody tr').each(function() {
            if ($(this).find('td:last').text().trim() === 'Applied') {
                $(this).toggle(areRowsHidden); // Toggle visibility of rows with 'Applied'
            } else {
                $(this).toggle(!areRowsHidden); // Toggle visibility of other rows
            }
        });
        areRowsHidden = !areRowsHidden; // Toggle the flag value
    });


    $('#selectAll').click(function() {
      $('.selectSingle').prop('checked', this.checked);
    });

    $('.selectSingle').click(function() {
      if ($('.selectSingle:checked').length == $('.selectSingle').length) {
        $('#selectAll').prop('checked', true);
      } else {
        $('#selectAll').prop('checked', false);
      }
    });


    $('#couponCode').change(function() {
      var name = $(this).val();
      $.ajax({
        url: "{{ route('institute.CoprporateCouponlists') }}/{{$corporate->id}}",
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}",
          name: name
        },
        success: function(response) {
          console.log(response);
          $('.table-striped-coupon').empty();

          $('.couponValue').text(response.codeValue);
          $('.prefixtxt').text(response.prefix);
          $('.totalcount').text(response.counts);
          $('.appliedCount').text(response.appliedCount);
          $('.issuedCount').text(response.issuedCount);

          $.each(response.coupons, function(index, coupon) {
            var row = "<tr>" +
              "<td><input type='checkbox' class='selectSingle'></td>" +
              "<td style='font-size: 13px'>" + (parseInt(index)+1) + "</td>" +
              "<td style='font-size: 13px'>" + coupon.prefix + "</td>" +
              "<td style='font-size: 13px'>" + coupon.name + "</td>" +
              "<td style='font-size: 13px'>" + coupon.couponcode + "</td>" +
              "<td style='font-size: 13px'>" + (coupon.status ? (coupon.is_applied ? 'Applied' : 'Active') : 'Inactive') + "</td>" +
              "</tr>";
            $('.table-striped-coupon').append(row);
          });
        },
        error: function(xhr, status, error) {
          // Handle error response
          console.error(xhr.responseText);
        }
      });
    });
  });


</script>
<!-- /#page-content-wrapper -->
@endsection('content')