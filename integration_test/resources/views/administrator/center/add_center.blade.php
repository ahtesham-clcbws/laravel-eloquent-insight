@extends('administrator.layouts.master')
@section('content')

<?php

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$country = "";
$state = '';
$city = '';

if ($center?->state_id) {
   $state = DB::table('states')->where('id', $center?->state_id)->first()?->name;
}


?>
<style>
    .line-with-button {
            position: relative;
        }
        .round-button {
         position: absolute;
         right: 0;
         margin-top: -16px;
         margin-right: 26px;
         transform: translateY(-50%);
         background-color: #007bff;
         color: white;
         border: none;
         border-radius: 50%;
         width: 30px;
         height: 30px;
         text-align: center;
         cursor: pointer;
}
        .round-button:focus {
            outline: none;
        }
</style>
<div class="container pagecontentbody">
   <div class="tab-content">
      <div class="row pt-3">
         <div class="col">
            <h5>
               Add Exam Center
            </h5>
         </div>
      </div>
      <div class="pagebody row pt-2">
         <div class="container col-md-9" style="border: 1px solid #c6cbd0;padding: 8px 25px;">
            <form method="post" action="{{route('admin.saveCenter')}}" enctype="multipart/form-data">
               @csrf
               <input type="hidden" name="id" value="{{$center?->id}}" autocomplete="off">
               <div class="row mt-2 ">
                  <div class="col-md-6 col mb-2">
                     <label class="form-label">State<span class="text-danger">*</span></label>
                     <select name="state_id" class="form-control form-select" style="height:calc(2rem + 2px) !important; padding:0 0 0 3px !important" id="state" onchange="selectDisctrict(this.value)" required>
                        <option value="">--Select state--</option>
                        @foreach($states as $state)
                        <option value="{{$state->id}}" {{$center?->state_id == $state->id ? 'selected' : ''}}>{{$state->name}}</option>
                        @endforeach
                     </select>
                     @error('state_id')
                     <div class="text-danger">{{$message}}</div>
                     @enderror
                  </div>
                  <div class="col-md-6 col mb-2">
                     <label class="form-label">City<span class="text-danger">*</span></label>
                     <select name="city_id" class="form-control form-select" style="height:calc(2rem + 2px) !important; padding:0 0 0 3px !important" id="district" required>
                        <option value="">--Select City--</option>
                     </select>
                     @error('city_id')
                     <div class="text-danger">{{$message}}</div>
                     @enderror
                  </div>
                  <div class="col-md-6 mb-2">
                     <label class="form-label">Test Center A<span class="text-danger">*</span></label>
                     <input required pattern="[A-Za-z ]+" class="form-control" style="height:calc(2rem + 2px) !important; padding:0 0 0 3px !important" placeholder="Test Center A" value="{{$center?->center_name}}" name="center_namea" />
                     @error('center_name')
                     <div class="text-danger">{{$message}}</div>
                     @enderror
                  </div>
                  <div class="col-md-6 mb-2">
                     <label class="form-label">Test Center B</label>
                     <input  pattern="[A-Za-z ]+" class="form-control" style="height:calc(2rem + 2px) !important; padding:0 0 0 3px !important" placeholder="Test Center B" value="" name="center_nameb" />
                     @error('center_name')
                     <div class="text-danger">{{$message}}</div>
                     @enderror
                  </div>
                  <div class="col-md-6 col mb-2">
                     <label class="form-label">Address<span class="text-danger">*</span></label>
                     <input required class="form-control" style="height:calc(2rem + 2px) !important; padding:0 0 0 3px !important" name="addressa" placeholder="Enter address" value="{{$center?->address}}" required />
                     @error('address')
                     <div class="text-danger">{{$message}}</div>
                     @enderror
                  </div>
                  <div class="col-md-6 col mb-2">
                     <label class="form-label">Address</label>
                     <input class="form-control" style="height:calc(2rem + 2px) !important; padding:0 0 0 3px !important" name="addressb" placeholder="Enter address" value="" />
                     @error('address')
                     <div class="text-danger">{{$message}}</div>
                     @enderror
                  </div>

                  <div class="col-md-6 col mb-2">
                     <label class="form-label">Landmark<span class="text-danger">*</span></label>
                     <input required type="text" placeholder="Enter landmark" class="form-control" style="height:calc(2rem + 2px) !important; padding:0 0 0 3px !important" name="landmarka" value="{{$center?->landmark}}" />
                     @error('landmark')
                     <div class="text-danger">{{$message}}</div>
                     @enderror
                  </div>

                  <div class="col-md-6 col mb-2">
                     <label class="form-label">Landmark</label>
                     <input type="text" placeholder="Enter landmark" class="form-control" style="height:calc(2rem + 2px) !important; padding:0 0 0 3px !important" name="landmarkb" value="" />
                     @error('landmark')
                     <div class="text-danger">{{$message}}</div>
                     @enderror
                  </div>
                  <div class="col-md-6 col col mb-2">
                     <label class="form-label">Pincode<span class="text-danger">*</span><small>[Max Digits:6]</small></label>
                     <input required type="text" pattern="[0-9]{6}" placeholder="Enter pincode" class="form-control" style="height:calc(2rem + 2px) !important; padding:0 0 0 3px !important" name="pincodea" value="{{$center?->pincode}}" required />
                     @error('pincode')
                     <div class="text-danger">{{$message}}</div>
                     @enderror
                  </div>
                  <div class="col-md-6 col col mb-2">
                     <label class="form-label">Pincode<small>[Max Digits:6]</small></label>
                     <input type="text" pattern="[0-9]{6}" placeholder="Enter pincode" class="form-control" style="height:calc(2rem + 2px) !important; padding:0 0 0 3px !important" name="pincodeb" value="" />
                     @error('pincode')
                     <div class="text-danger">{{$message}}</div>
                     @enderror
                  </div>
               </div>
               <hr class="line-with-button">
               <span class="round-button add-center-c">+</span>
               <div class="row center-c-cl" style="display: none;">
                  <div class="col-md-6 mb-2">
                     <label class="form-label">Test Center C</label>
                     <input pattern="[A-Za-z ]+" class="form-control" style="height:calc(2rem + 2px) !important; padding:0 0 0 3px !important" placeholder="Test Center C" value="" name="center_namec" />
                     @error('center_name')
                     <div class="text-danger">{{$message}}</div>
                     @enderror
                  </div>
                  <div class="col-md-6 col mb-2">
                     <label class="form-label">Address<span class="text-danger">*</span></label>
                     <input class="form-control" style="height:calc(2rem + 2px) !important; padding:0 0 0 3px !important" name="addressc" placeholder="Enter address" value=""  />
                     @error('address')
                     <div class="text-danger">{{$message}}</div>
                     @enderror
                  </div>
                  <div class="col-md-6 col mb-2">
                     <label class="form-label">Landmark</label>
                     <input type="text" placeholder="Enter landmark" class="form-control" style="height:calc(2rem + 2px) !important; padding:0 0 0 3px !important" name="landmarkc" value="" />
                     @error('landmark')
                     <div class="text-danger">{{$message}}</div>
                     @enderror
                  </div>

                  <div class="col-md-6 col col mb-2">
                     <label class="form-label">Pincode<span class="text-danger">*</span><small>[Max Digits:6]</small></label>
                     <input type="text" pattern="[0-9]{6}" placeholder="Enter pincode" class="form-control" style="height:calc(2rem + 2px) !important; padding:0 0 0 3px !important" name="pincodec" value="" />
                     @error('pincode')
                     <div class="text-danger">{{$message}}</div>
                     @enderror
                  </div>
                  <div class="col-12">
                     <hr />
                  </div>
               </div>
               <div class="row justify-content-center">
                  <div class="col-md-3 d-grid">
                     <button type="submit" class="btn btn-theme btn-primary submitform">Add Center</button>
                  </div>

               </div>
            </form>
         </div>
      </div>
   </div>
</div>

<script>
   function selectDisctrict(state) {
      console.log(state)
      console.log('vvv')
      if (state) {
         fetchDistricts(state);
      } else {
         $('#district').html('<option value="">--Select district--</option>');
      }
   }
   $('.add-center-c').on('click',function(){
      $('.center-c-cl').toggle();

     if($('.center-c-cl').is(':visible')){
      $('.add-center-c').text('-');
     }else
     {
      $('.add-center-c').text('+');
     }

      $('.center-c-cl').each(function() {
        var $this = $(this);
    });
   })
   $('#state').val({{  $center?-> state_id ?? 'null' }}).trigger('change');

   // Function to fetch subjects based on subcategory
   function fetchDistricts(state) {
      $.get('/districts/' + state, function(data) {
         $('#district').empty().append('<option value="">--Select District/City--</option>');
         $.each(data, function(index, district) {
            var selected = ({{ $center?->city_id ?? 'null'}} == district.id) ? 'selected' : '';
            $('#district').append('<option value="' + district.id + '" ' + selected + '>' + district.name + '</option>');
         });
      });
   }
</script>
@endsection