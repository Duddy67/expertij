<div class="col-md-6 form-group">
    <label>{{ __('labels.generic.attestation') }}</label>
    <div>
        <button type="button" class="btn btn-primary" id="modalButton" data-bs-toggle="modal" data-bs-target="#myModal">
          Open modal
        </button>
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <div class="embed-responsive embed-responsive-16by9">
                  <iframe class="modal-iframe embed-responsive-item" id="documentIframe" src="{{ route('cms.filemanager.index') }}" frameborder="0"></iframe>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
  </div>
</div>
