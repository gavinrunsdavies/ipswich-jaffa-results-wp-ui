<?php
wp_enqueue_style( 'jquery-ui-style', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css');
wp_enqueue_style( 'easy-autocomplete', plugins_url('/lib/easy-autocomplete.min.css', dirname(__FILE__) ));
wp_enqueue_script('jquery-ui-datepicker');
?>
<div class="section">
	<div class="formRankCriteria">
		<p>Current members of Ipswich JAFFA Running Club can use this form to cast their votes for their runner of the month. Each vote counts and the adult mens and adult ladies with the the most votes wins. You can now vote for more than one member in each category in the month, although you may only vote for the same member once.</p>
		<p>Please cast your vote for your John Jarrold Runner of the Month and be sure to select the correct month. At the start of the next month all votes will be reset.</p>
		<p>To help certify genuine votes and exclude fraudent votes your name and date of birth will be requested. These are only used for authentication purposes and to prevent multi voting for the same person.</p>
		<style>
			.hidden {
				display: none;
			}

			.help-block {
				display: none;
				border: 1px dotted #f00;
				color: #f00;
			}

			.row * {
				box-sizing: border-box;
			}

			/* Create two equal columns that floats next to each other */
			.col-50 {
				float: left;
				width: 50%;
				padding: 10px;
			}

			/* Clear floats after the columns */
			.row:after {
				content: "";
				display: table;
				clear: both;
			}

			.has-error {
				border: 1px solid #f00;
			}

			#ui-datepicker-div {
				/* to layer over the typeahead automcomplete text boxes */
				z-index: 10 !important;
			}
			
			.easy-autocomplete ul {
				margin: 0;
			}
			
			#alert-ok, #alert-authentication-failed {
				padding: 1em;
			}
			
			#alert-ok h4, #alert-authentication-failed h4 {
				text-align: center
			}
		</style>
    	<form class="formRankCriteria" id="runnerOfTheMonthVote">
			<label for="yourName">Your name</label>
			<input
				   autocomplete="off"
				   id="yourName"
				   placeholder="Your name"
				   type="text"/>
			<p><span class="help-block" id="yourNameHelpBlock">Please provide your name. This is required for authentication purposes.</span></p>
            <label for="yourDob">Date of birth</label>
			<input
				id="yourDob"
				placeholder="Your date of birth"
				type="text"/>
			<p><span class="help-block" id="yourDateOfBirthHelpBlock">Please provide your date of birth. This is required for authentication purposes.</span></p>
        <?php
        if (date("j") <= 5) 
        {
        ?>
            <label for="voteMonthAndYear">Month</label>       
			<select
				name="month"			
				id="voteMonthAndYear">
				<?php
				$monthWithZeroes = date("m");
				printf('<option value="">Please Select...</option>');
				printf('<option value="'.date("Y-n").'">'.date('F', strtotime("2000-$monthWithZeroes-01")).'</option>');
				if (date("n") == 1) {
					$year = date("Y") - 1;
					$month = 1;
					printf('<option value="'.$year.'-'.$month.'">December</option>');
				} else {
					$year = date("Y");
					$month = date("n") - 1;
					$monthWithZeroes = date("m") - 1;
					printf('<option value="'.$year.'-'.$month.'">'.date('F', strtotime("2012-$monthWithZeroes-01")).'</option>');
				}
				?>
			</select>
            <p><span class="help-block" id="monthHelpBlock">Please choose which month.</span></p>
        <?php        
        }
        else
        {
          printf('<input id="voteMonthAndYear" type="hidden" value="'.date("Y-n").'" name="monthAndYear"/>');
        }
        ?>
			<div class="row">
				<div class="col-50">        
					<label for="menVote">Adult Men</label>
					<input
						   autocomplete="off"
						   id="menVote"
						   placeholder="Start typing to see list of members..."
						   type="text">
					<label for="menVoteReason">Reason</label>
					<textarea
						id="menVoteReason"
						maxlength="500"
						rows="3"></textarea>
					<p><span class="help-block" id="menReasonHelpBlock">Please provide a reason for the nomination.</span></p>
				</div>
				<div class="col-50"> 
					<label for="ladiesVote">Adult Ladies</label>
					<input
						   autocomplete="off"
						   id="ladiesVote"
						   placeholder="Start typing to see list of members..."
						   type="text">
					<label for="ladiesVoteReason">Reason</label>
					<textarea
						id="ladiesVoteReason"
						maxlength="500"
						rows="3"></textarea>
					<p><span class="help-block" id="ladiesReasonHelpBlock">Please provide a reason for the nomination.</span></p>
				</div>                     
			</div>
			<div class="ui-widget-content ui-corner-all hidden" id="alert-ok">			
				<h4 class="ui-widget-header ui-corner-all">Thank you for voting</h4>
				<p>You can vote for as many people as you like, although you may only vote for
					an individual once in a calendar month.</p>
			</div>
			<div class="ui-widget-content ui-corner-all hidden" id="alert-authentication-failed">			
				<h4 class="ui-widget-header ui-corner-all">Your name and date of birth do not match our records</h4>
				<p>Your votes have not been cast. Please try again.</p>
			</div>
			<input id="nominate" type="button" value="Nominate" />
		</form>		
	</div>
	<table class="display" id="runner-of-the-month-winners-table">
		<caption>Past John Jarrold Runner of the Month winners</caption>
		<thead>
			<tr>
				<th>Year</th>
				<th>Month</th>
				<th></th>
				<th>Men</th>
				<th>Ladies</th>
				<th>Boys</th>
				<th>Girls</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
</div>
<script type="text/javascript" src="<?php echo plugins_url('/lib/wp-jquery.easy-autocomplete.min.js', dirname(__FILE__) ); ?>"></script>
<script type="text/javascript">
	jQuery(document).ready(function ($) {
		var runnersTable = $('#runner-of-the-month-winners-table').DataTable({
	      pageLength : 12,
		  order: [[ 0, "desc" ],  [2, "desc" ]],
	      columns : [{
			  data : "year"
			}, {
	          data : "month",
			  render : function (data) {
				  return getMonthName(data);
			  },
	          searchable : false,
	          sortable : true,
	          visible : true			  
	        }, {
				data: "month",
				"visible" : false,
				"searchable": false,
				"sortable": true 
			 }, {
	          data : "winners",
			  defaultContent: "<i>None set</i>",
	          render : function (data, type, row) {
				for(var i = 0; i < data.length; i++) {
					if (data[i].category == 'Men') {
						return getRunnerAnchorHtml(data[i].id, data[i].name);
					}
				}
	            
	            return;
	          }
	        }, {
	          data : "winners",
			  defaultContent: "<i>None set</i>",
	          render : function (data, type, row) {
				for(var i = 0; i < data.length; i++) {
					if (data[i].category == 'Ladies') {
						return getRunnerAnchorHtml(data[i].id, data[i].name);
					}
				}
	            
	            return;
	          }
	        }, {
	          data : "winners",
			  defaultContent: "<i>None set</i>",
	          render : function (data, type, row) {
				for(var i = 0; i < data.length; i++) {
					if (data[i].category == 'Boys') {
						return getRunnerAnchorHtml(data[i].id, data[i].name);
					}
				}
	            
	            return;
	          }
	        }, 
			{
	          data : "winners",
			  defaultContent: "<i>None set</i>",
	          render : function (data, type, row) {
				for(var i = 0; i < data.length; i++) {
					if (data[i].category == 'Girls') {
						return getRunnerAnchorHtml(data[i].id, data[i].name);
					}
				}
	            
	            return;
	          }
	        }
	      ],
	      processing : true,
	      autoWidth : false,
	      scrollX : true,
	      ajax : getAjaxRequest('/wp-json/ipswich-jaffa-api/v3/runnerofthemonth/winners')
	    });
		
		getRunners();
	  
		function getRunnerAnchorHtml(id, name) {
			var resultsUrl = '<?php echo $memberResultsPageUrl; ?>';
			var anchor = '<a href="' + resultsUrl;
				anchor += '?runner_id=' + id;
				anchor += '">' + name + '</a>';
				
			return anchor;
		}
	  
		function getMonthName(number) {
			if (number < 0 || number > 11)
				return "";
		 
			var monthNames = [ "January", "February", "March", "April", "May", "June", 
						"July", "August", "September", "October", "November", "December" ];
						
			return monthNames[number];
		}

		function getAjaxRequest(url) {
			return {
			"url" : '<?php echo esc_url( home_url() ); ?>' + url,
			"method" : "GET",
			"headers" : {
				"cache-control" : "no-cache"
			},
			"dataSrc" : ""
			};
		}
	  		
	  	function getRunners() {			
			$.getJSON( '<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/runners' )
				.done(function( data ) {
					autoComplete(data, "#yourName", voter);
					autoComplete(data.filter(a => a.sex == "Male"), "#menVote", maleNomination);
					autoComplete(data.filter(a => a.sex == "Female"), "#ladiesVote", femaleNomination);
				});
		}
	
		function autoComplete(data, selector, selection) {
			
			var options = {
				data: data,
				getValue: "name",
				list: {
					match: {
						enabled: true
					},
					onSelectItemEvent: function() {
						selection.id = $(selector).getSelectedItemData().id;
					}
				}
			};
			
			$(selector).easyAutocomplete(options);
		}
		      
		$('#yourDob').datepicker({        
			dateFormat: "yy-mm-dd",
			changeMonth: true,
      		changeYear: true,
			maxDate: "-8y",
			minDate: "-120y",
			yearRange: "-120:-8"
		});
	
		var maleNomination = {id: 0};
		var femaleNomination = {id: 0};
		var voter = {id: 0};
	
	function validateRequest() {
		var success = true;
		$('.help-block').hide();
		$('#runnerOfTheMonthVote .has-error').removeClass('has-error');			

		if (voter.id == 0) {
			$('#yourName').addClass( "has-error" );
			$('#yourNameHelpBlock').show("slow");
			success = false;
		}
		
		if ($('#yourDob').val() == ''){
			$('#yourDob').addClass( "has-error" );
			$('#yourDateOfBirthHelpBlock').show("slow");			
			success = false;
		}
		
		if (maleNomination.id > 0 && $('#menVoteReason').val() == '') {
			$('#menVoteReason').addClass( "has-error" );
			$('#menReasonHelpBlock').show("slow");
			success = false;
		}			
		
		if (femaleNomination.id > 0 && $('#ladiesVoteReason').val() == '') {
			$('#ladiesVoteReason').addClass( "has-error" );
			$('#ladiesReasonHelpBlock').show("slow");
			success = false;
		}
		
		if (maleNomination.id == 0  && femaleNomination.id == 0) {
			$('#ladiesVoteReason').addClass( "has-error" );
			$('#menVoteReason').addClass( "has-error" );
			success = false;
		}
    
		if ($('#voteMonthAndYear') != undefined && $('#voteMonthAndYear').val() == '') {
			$('#voteMonthAndYear').addClass( "has-error" );
			success = false;
		}
		
		return success;
	}
	
	 $("#nominate").click(function(e) {
		e.preventDefault();
		$('#alert-ok').hide();
		$('#alert-authentication-failed').hide();
		
	    var success = validateRequest();
		
		if (!success)
			return;

		var date = $('#voteMonthAndYear').val().split('-');
			var votes = { 				 				
				voterId: voter.id,
				voterDateOfBirth: $('#yourDob').val(),
				month: date[1],
				year: date[0]
		};
		
		if (maleNomination.id > 0) {
			votes.men = {
				runnerId: maleNomination.id,
				reason: $('#menVoteReason').val()
			}
		}
		
		if (femaleNomination.id > 0) {
			votes.ladies = {
				runnerId: femaleNomination.id,
				reason: $('#ladiesVoteReason').val()
			}
		}
    
		var jqxhr = $.post(
			'<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/runnerofthemonth/vote', 
			votes,
			// Success - HTTP 200
			function(data) 
			{				
				$('#ladiesVoteReason, #ladiesVote, #menVoteReason, #menVote').val("");
				maleNomination.id = 0;
				femaleNomination.id = 0;
				$('#runnerOfTheMonthVote .has-error').removeClass('has-error');				
				$('#alert-ok').removeClass('hidden').show("fade", {}, 500, fadeAway('#alert-ok'));
			},
			"json"
		);
		
		jqxhr.error(function(xhr) {
			if (xhr.status == 401) {								
				$('#alert-authentication-failed').removeClass('hidden').show("fade", 500, fadeAway('#alert-authentication-failed'));
			} else {
				alert("Unknown error");
			}
		});
	 });
	 
	function fadeAway(element) {
    	setTimeout(function() {
        	//$(element).hide("fade"); TODO
      	}, 10000 );
    };
});
</script>