<ol>
<li>Result 1: <a id="1" href="#"><span class="result glyphicon glyphicon-thumbs-up" aria-hidden="true"><span class="hidden">Vote</span></span></a></li>
<li>Result 2: <a id="2" href="#"><span class="result glyphicon glyphicon-thumbs-up" aria-hidden="true"><span class="hidden">Vote</span></span></a></li>
<li>Result 3: <a id="3" href="#"><span class="result glyphicon glyphicon-thumbs-up" aria-hidden="true"><span class="hidden">Vote</span></span></a></li>
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
            <span class="help-block" id="ukaNumberHelpBlock">Please enter your UK Athletics membership number.</span>
          </div>     
          <div class="form-group">
            <label for="lastName" class="col-form-label">Last Name:</label>
            <input type="text" class="form-control" id="lastName">
            <span class="help-block" id="lastNameHelpBlock">Please enter your last name as recorded with UK Athletics.</span>
          </div> 
          <div class="alert alert-danger alert-dismissible fade in" id="alertAuthenticationFailed" role="alert" style="display: none">
            <button aria-label="Close" class="close" data-hide="alert" type="button">
                <span aria-hidden="true">×</span>
            </button>
            <h4>UKA Athentication Failed</h4>
            <p class="message"></p>
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
<!-- Error Modal for when Cookie exists -->
<div class="modal fade" id="runnerOfTheMonthErrorModal" tabindex="-1" role="dialog" aria-labelledby="runnerOfTheMonthAuthModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="runnerOfTheMonthAuthModalTitle">Result Voting Failed</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">	    
        <p class="message"></p>         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
    const cookieName = 'runnerOfTheMonthAuth';
    var resultId = 0;
    $(".result").click(function(e) {
		  e.preventDefault();
      // Get #id of result clicked
      resultId = $(this).parent().attr('id');

      var runnerOfTheMonthAuthCookie = Cookies.getJSON(cookieName);
      if (runnerOfTheMonthAuthCookie === undefined) {
        $('#runnerOfTheMonthAuthModal').modal('show');
      } else {
        // Read cookie to get last name and URN
        castVote(resultId, runnerOfTheMonthAuthCookie.ukaNumber, runnerOfTheMonthAuthCookie.lastName, false);
      }

      $(this).css("color", "#ef922e");
    });

    $("#authenticateUser").click(function(e) {
      e.preventDefault();

      $('#runnerOfTheMonthAuthModal .has-error').removeClass('has-error');	

      var success = validateRequest();
		
		  if (!success)
			  return;

      var ukaNumber = $('#UKA-number').val();
      var lastName = $('#lastName').val();
    
      castVote(resultId, ukaNumber, lastName, true); 
    });

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

    function castVote(resultId, ukaNumber, lastName, isAuthentciationModalShown) {
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
          var cookieValue = createCookieValue(ukaNumber, lastName);
          Cookies.set(cookieName, cookieValue, { expires: null, path: '/' });
          $('#runnerOfTheMonthAuthModal').modal('hide');
        },
        "json"
      );

      jqxhr.error(function(xhr) {
        if (xhr.status == 401) {								
          $('#alertAuthenticationFailed .message, #runnerOfTheMonthErrorModal .message').text(xhr.responseJSON.message)
        } else {
          $('#alertAuthenticationFailed .message, #runnerOfTheMonthErrorModal .message').text("Unknown error. Please try again.")
        }       

        if (!isAuthentciationModalShown)
          $('#runnerOfTheMonthErrorModal').modal('show');
        else {
          $('#alertAuthenticationFailed').show();
        }
      });
    }

     $("[data-hide]").on("click", function(){
        $("." + $(this).attr("data-hide")).hide()
    });  		
	});
</script>