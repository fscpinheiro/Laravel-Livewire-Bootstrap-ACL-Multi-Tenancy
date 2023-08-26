@extends('layouts.master')
@section('title', 'Gerencia de Funções')
@section('styles')
<style>

</style>
@endsection
@section('conteudo')
<div class="container-fluid flex-grow-1 container-p-y">
  <div class="row mb-4">
    <div class="col-12 col-sm-6 col-lg-4 mb-4">
      <div class="card">
        <div class="card-body text-center">
          <i class="mb-3 ti ti-home ti-lg"></i>
          <h5>Add New Address</h5>
          <p>Ready to use form to collect user address data with validation and custom input support.</p>
          <button
            type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#addNewAddress"
          >
            Show
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- Add New Address Modal -->
  <div class="modal fade" id="addNewAddress" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="address-title mb-2">Add New Address</h3>
            <p class="text-muted address-subtitle">Add new address for express delivery</p>
          </div>
          <form id="addNewAddressForm" class="row g-3" onsubmit="return false">
           
            
            <div class="col-12">
              <label class="form-label" for="modalAddressCountry">Country</label>
              <select
                id="modalAddressCountry"
                name="modalAddressCountry"
                class="select2 form-select"
                data-allow-clear="true"
              >
                <option value="">Select</option>
                <option value="Australia">Australia</option>
                <option value="Bangladesh">Bangladesh</option>
                <option value="Belarus">Belarus</option>
                <option value="Brazil">Brazil</option>
                <option value="Canada">Canada</option>
                <option value="China">China</option>
                <option value="France">France</option>
                <option value="Germany">Germany</option>
                <option value="India">India</option>
                <option value="Indonesia">Indonesia</option>
                <option value="Israel">Israel</option>
                <option value="Italy">Italy</option>
                <option value="Japan">Japan</option>
                <option value="Korea">Korea, Republic of</option>
                <option value="Mexico">Mexico</option>
                <option value="Philippines">Philippines</option>
                <option value="Russia">Russian Federation</option>
                <option value="South Africa">South Africa</option>
                <option value="Thailand">Thailand</option>
                <option value="Turkey">Turkey</option>
                <option value="Ukraine">Ukraine</option>
                <option value="United Arab Emirates">United Arab Emirates</option>
                <option value="United Kingdom">United Kingdom</option>
                <option value="United States">United States</option>
              </select>
            </div>
            
            <div class="col-12 text-center">
              <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
              <button
                type="reset"
                class="btn btn-label-secondary"
                data-bs-dismiss="modal"
                aria-label="Close"
              >
                Cancel
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!--/ Add New Address Modal -->
</div>
@endsection
@section('vendorscripts')
@endsection

@section('scripts')
<script>
  // Select2 (jquery)
$(function () {
  const select2 = $('.select2');

  // Select2 Country
  if (select2.length) {
    select2.each(function () {
      var $this = $(this);
      $this.wrap('<div class="position-relative"></div>').select2({
        placeholder: 'Select value',
        dropdownParent: $this.parent()
      });
    });
  }
});
</script>
@endsection