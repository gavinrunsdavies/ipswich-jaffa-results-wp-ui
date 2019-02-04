<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#runnerOfTheMonthAuthModal">
  Launch demo modal
</button>
<div>
<ol>
<li  id="1"><span class="result">Vote Result 1</span></li>
<li  id="2"><span class="result">Vote Result 2</span></li>
<li  id="3"><span class="result">Vote Result 3</span></li>
</ol>

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
	      <p>Please enter your Ipswich JAFFA RC UK Athletics membership numnber and last name. This will validate your membership with Ipswich JAFFA 
		  and in doing so enable you to vote on member results that you feel deserve to win the John Jarrold Runner of the Month award. 
		  Each vote counts and the adult mens and adult ladies with the the most votes wins.</p>
          <div class="form-group">
            <label for="UKA-number" class="col-form-label">UKA Number:</label>
            <input type="text" class="form-control" id="UKA-number">
          </div>     
          div class="form-group">
            <label for="lastName" class="col-form-label">Last Name:</label>
            <input type="text" class="form-control" id="lastName">
          </div>                
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="authenticateUser">Authenticate</button>
      </div>
	  </form>
    </div>
  </div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
    const cookieName = 'runnerOfTheMonthAuth';

    $(".result").click(function(e) {
		  e.preventDefault();
      // Get #id of result clicked
      var resultId = $(this).parent().attr('id');

      var runnerOfTheMonthAuthCookie = $.cookie(cookieName);
      if (typeof runnerOfTheMonthAuthCookie === 'undefined') {
        $('#runnerOfTheMonthAuthModal').modal('show');
      } else {
        // Read cookie to get last name and URN
        var cookieValue = JSON.parse(runnerOfTheMonthAuthCookie);        
        castVote(resultId, cookieValue.ukaNumber, cookieValue.lastName);
      }
    }

    $("#authenticateUser").click(function(e) {
      e.preventDefault();

      var success = validateRequest();
		
		  if (!success)
			  return;

      setAuthenticateCookieAndVote();
    }

    function validateRequest() {
      var success = true;    
      
      if ($.isNumeric($('#UKA-number').val()) === false) {
        $('#UKA-number').parents('div.form-group').first().addClass( "has-error" );
        success = false;
      }
          
      if ($('#lastName') != undefined && $('#lastName').val() == '') {
        $('#lastName').parents('div.form-group').first().addClass( "has-error" );
        success = false;
      }
      
      return success;
    }

    function createCookieValue(ukaNumber, lastName) {
      return { 				
        ukaNumber: ukaNumber,
        lastName: lastName
      };
    }

    function setAuthenticateCookieAndVote() {
      var ukaNumber = $('#UKA-number').val();
      var lastName = $('#lastName').val();
      var cookieValue = createCookieValue(ukaNumber, lastName);
      $.cookie(cookieName, cookieValue, { expires: null, path: '/' });
      castVote(resultId, ukaNumber, lastName);
    } 

    function castVote(resultId, ukaNumber, lastName) {
      var votes = { 				
        voterId: ukaNumber,
        lastName: lastName
      };
    
      var jqxhr = $.post(
        '<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/runnerofthemonth/resultsvote/' + resultId, 
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