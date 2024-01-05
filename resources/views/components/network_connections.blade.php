<div class="row justify-content-center mt-5">
  <div class="col-12">
    <div class="card shadow  text-white bg-dark">
      <div class="card-header">Coding Challenge - Network connections</div>
      <div class="card-body">
        <div class="btn-group w-100 mb-3" role="group" aria-label="Basic radio toggle button group">
          <input type="radio" class="btn-check" name="active_tab" value="suggestions" id="btnradio1" autocomplete="off" checked>
          <label class="btn btn-outline-primary" for="btnradio1" id="get_suggestions_btn">
              Suggestions (<span id="suggestions_count">{{ $counts['suggestionsCount'] }}</span>)
          </label>

          <input type="radio" class="btn-check" name="active_tab" value="sent_requests" id="btnradio2" autocomplete="off">
          <label class="btn btn-outline-primary" for="btnradio2" id="get_sent_requests_btn">
              Sent Requests (<span id="sent_requests_count">{{ $counts['sentRequestsCount'] }}</span>)
          </label>

          <input type="radio" class="btn-check" name="active_tab" value="received_requests" id="btnradio3" autocomplete="off">
          <label class="btn btn-outline-primary" for="btnradio3" id="get_received_requests_btn">
              Received Requests (<span id="received_requests_count">{{ $counts['receivedRequestsCount'] }}</span>)
          </label>

          <input type="radio" class="btn-check" name="active_tab" value="connections" id="btnradio4" autocomplete="off">
          <label class="btn btn-outline-primary" for="btnradio4" id="get_connections_btn">
              Connections (<span id="connections_count">{{ $counts['connectionsCount'] }}</span>)
          </label>
        </div>
        <hr>
        <div id="content">
            @if(empty($counts['suggestionsCount']))
                <span class="fw-bold">There are no records.</span>
            @endif
        </div>
          <div id="skeleton" class="d-none">
              @for ($i = 0; $i < 10; $i++)
                  <x-skeleton />
              @endfor
          </div>
          <div class="d-flex justify-content-center mt-2 py-3 d-none" id="load_more_btn_parent">
              <button class="btn btn-primary" onclick="" id="load_more_btn">Load more</button>
          </div>
      </div>
    </div>
  </div>
</div>
