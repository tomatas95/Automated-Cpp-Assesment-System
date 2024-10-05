<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="infoModalLabel">
            <i class="bi bi-lightbulb-fill text-info me-2"></i>
            {{ __("Before writing any code, a few things to consider") }}
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12 col-md-6 text-start p-2 p-md-3 bg-light rounded mb-3 mb-md-0">
              <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill text-success fs-2 me-2"></i>
                <h3 class="modal-grid-header modal-grid-header-left m-0">
                  {!! __("strongly_recommended_to_use_cins") !!}
                </h3>
              </div>
              <p class="mt-3">{{ __("Apart from variety of benefits, like making your program much more flexible during runtime allowing more freedom and not being too limited in terms of user's input but also ensuring automatization checks will be properly tested.") }}</p>
              <p class="mt-3">{{ __("This method not only provides seamless working environment, but allows you to work with array exercises in variety of complex tasks") }}</p>
              <img src="{{ asset('images/show-exercise-page/cin_use.png') }}" alt="Program utilizing the cin method for input" class="img-fluid w-100" data-bs-toggle="modal" data-bs-target="#imageModal1">
              <span class="text-muted picture-alt">{{ __("Recommended use of establishing important variables using cin method.") }}</span>

            </div>
  
            <div class="col-12 col-md-6 text-start p-2 p-md-3 bg-light rounded">
              <div class="d-flex align-items-center">
                <i class="bi bi-x-circle-fill text-danger fs-2 me-2"></i>
                <h3 class="modal-grid-header modal-grid-header-right m-0">
                  {!! __("strongly_not_recommended_to_use_hardcoding_values") !!}
                </h3>
              </div>
              <p class="mt-3">{{ __("Hardcoding values are not recommended as it reduces your program's flexibility and makes it very limited in use. If a person still wishes to use hardcoded values for inputs i.e array sizes or any numbers excluding constants and other variables where input is not necessary. If a person still wants to use hardcoded values, they should follow a certain formula by adding a var before the variable name like so:") }}
                <p class=mt-3>{{ __("Using hardcoded values also limits the exercise complexity, as working with arrays becomes quite a challenging task.") }}</p>
                <ul>
                  <li class="font-weight-bold var-list">var&lt;{{ __("variable name") }}&gt;</li>
                  <li class="font-weight-bold var-list">var_&lt;{{ __("variable name") }}&gt;</li>
                </ul>
              </p>
              <img src="{{ asset('images/show-exercise-page/hardcoded_use.png') }}" alt="Program utilizing the hardcoded value method for input" class="img-fluid w-100" data-bs-toggle="modal" data-bs-target="#imageModal2">
              <span class="text-muted picture-alt">{{ __("Guide how to use hardcoded values for important variables such as array sizes for automatization testing purposes.") }}</span>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="modal fade" id="imageModal1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-body">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <img src="{{ asset('images/show-exercise-page/cin_use.png') }}" class="img-fluid" alt="Cin Method Full Image">
        </div>
      </div>
    </div>
  </div>
  
  <div class="modal fade" id="imageModal2" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-body">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <img src="{{ asset('images/show-exercise-page/hardcoded_use.png') }}" class="img-fluid" alt="Hardcoded Value Full Image">
        </div>
      </div>
    </div>
  </div>
  