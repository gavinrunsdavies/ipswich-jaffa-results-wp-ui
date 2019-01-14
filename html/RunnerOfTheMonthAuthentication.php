<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#runnerOfTheMonthAuthModal">
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="runnerOfTheMonthAuthModal" tabindex="-1" role="dialog" aria-labelledby="runnerOfTheMonthAuthModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
	<form>
      <div class="modal-header">
        <h5 class="modal-title" id="runnerOfTheMonthAuthModalTitle">Ipswich JAFFA RC Member Authentication</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	      <p>Please enter your Ipswich JAFFA RC UK Athletics membership numnber. This will validate your membership with Ipswich JAFFA 
		  and in doing so enable you to vote on member results that you feel deserve to win the John Jarrold Runner of the Month award. 
		  Each vote counts and the adult mens and adult ladies with the the most votes wins.</p>
          <div class="form-group">
            <label for="UKA-number" class="col-form-label">UKA Number:</label>
            <input type="text" class="form-control" id="UKA-number">
          </div>                  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Authenticate</button>
      </div>
	  </form>
    </div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
    const cookieName = 'runnerOfTheMonthAuth';

    if (typeof $.cookie('cookie') === 'undefined') {
      authenticateUser();
    } else {
      castVote();
    }

    function authenticateUser() {
      // Authenticate user
      // Success
      $.cookie(cookieName, 'true', { expires: null, path: '/' });
      castVote(resultId);
    } 

    function castVote(resultId, ukaNumber) {
      var votes = { 				
        voterId: ukaNumber
      };
    
      var jqxhr = $.post(
        '<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/runnerofthemonth/vote/' + resultId, 
        votes,
        // Success - HTTP 200
        function(data) 
        {					
          // TODO change vote icon to a tick or such  like						
          $('#alert-ok').removeClass('hidden');
        },
        "json"
      );

      jqxhr.error(function(xhr) {
        if (xhr.status == 401) {								
          $('#alert-authentication-failed').removeClass('hidden');
        } else {
          alert("Unknown error");
        }
      });
    }  

    // Failure

		
	});
</script>