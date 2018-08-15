<p>Current members of Ipswich JAFFA Running Club can use this form to cast their votes for their runner of the month. Each vote counts and the adult mens and adult ladies with the the most votes at the next months committee meeting (second Tuesday of every month) wins. From September 2016 you can now vote for more than one member in each category in the month, although you may only vote for the same member once.</p>
<p>Please cast your vote for your John Jarrold Runner of the Month. At the start of the next month all votes will be reset.</p>
<p>To help certify genuine votes and exclude fraudent votes your name and date of birth will now be requested. These are only used for authentication purposes and to prevent multi voting for the same person.</p>
<div class="section">
    <form id="runnerOfTheMonthVote" class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-4 control-label" for="yourName">Name</label>
            <div class="col-sm-8">
                <div class="typeahead__container">
                    <div class="typeahead__field">
                        <span class="typeahead__query">
                            <input
                                aria-describedby="yourNameHelpBlock"
                                autocomplete="off"
                                class="js-typeahead"
                                id="yourName"
                                placeholder="Your name"
                                type="search">
                        </span>                        
                    </div>
                </div>
				<span class="help-block" id="yourNameHelpBlock">Please provide your name. This is required for authentication purposes.</span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="yourDob">Date of birth</label>
            <div class="col-sm-8">
                <input
                    aria-describedby="yourDateOfBirthHelpBlock"
                    class="form-control"
                    id="yourDob"
                    placeholder="Your date of birth"
                    type="text">
                <span class="help-block" id="yourDateOfBirthHelpBlock">Please provide your date of birth. This is required for authentication purposes.</span>
            </div>
        </div>
        <?php
        if (date("j") <= 5) 
        {
        ?>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="voteMonthAndYear">Month</label>
            <div class="col-sm-8">
                <select
                    name="month"
                    aria-describedby="monthHelpBlock"
                    class="form-control"
                    id="voteMonthAndYear">
                    <?php
                    $monthWithZeroes = date("m");
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
                <span class="help-block" id="monthHelpBlock">Please choose which month.</span>
            </div>
        </div>
        <?php        
        }
        else
        {
          printf('<input id="voteMonthAndYear" type="hidden" value="'.date("Y-n").'" name="monthAndYear"/>');
        }
        ?>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="menVote">Adult Men</label>
            <div class="col-sm-8">
                <div class="typeahead__container">
                    <div class="typeahead__field">
                        <span class="typeahead__query">
                            <input
                                autocomplete="off"
                                class="js-typeahead"
                                id="menVote"
                                placeholder="Start typing to see list of members..."
                                type="search">
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="menVoteReason">Reason</label>
            <div class="col-sm-8">
                <textarea
                    aria-describedby="menReasonHelpBlock"
                    class="form-control"
                    id="menVoteReason"
                    maxlength="500"
                    rows="3"></textarea>
                <span class="help-block" id="menReasonHelpBlock">Please provide a reason for the nomination.</span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="ladiesVote">Adult Ladies</label>
            <div class="col-sm-8">
                <div class="typeahead__container">
                    <div class="typeahead__field">
                        <span class="typeahead__query">
                            <input
                                autocomplete="off"
                                class="js-typeahead"
                                id="ladiesVote"
                                placeholder="Start typing to see list of members..."
                                type="search">
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="ladiesVoteReason">Reason</label>
            <div class="col-sm-8">
                <textarea
                    aria-describedby="ladiesReasonHelpBlock"
                    class="form-control"
                    id="ladiesVoteReason"
                    maxlength="500"
                    rows="3"></textarea>
                <span class="help-block" id="ladiesReasonHelpBlock">Please provide a reason for the nomination.</span>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-8">
                <button class="btn btn-default" id="nominate" type="submit">Nominate</button>
            </div>
        </div>
    </form>
    <div
        class="alert alert-success alert-dismissible fade in hidden"
        id="alert-ok"
        role="alert">
        <button aria-label="Close" class="close" data-dismiss="alert" type="button">
            <span aria-hidden="true">×</span>
        </button>
        <h4>Thank you for voting</h4>
        <p>You can vote for as many people as you like, although you may only vote for
            an individual once in a calendar month.</p>
    </div>
    <div
        class="alert alert-warning alert-dismissible fade in hidden"
        id="alert-duplicate-vote"
        role="alert">
        <button aria-label="Close" class="close" data-dismiss="alert" type="button">
            <span aria-hidden="true">×</span>
        </button>
        <h4>Thank you for voting</h4>
        <p>It looks like you have already voted for one of the members. You may only
            vote for an individual once in a calendar month.</p>
    </div>
    <div
        class="alert alert-danger alert-dismissible fade in hidden"
        id="alert-authentication-failed"
        role="alert">
        <button aria-label="Close" class="close" data-dismiss="alert" type="button">
            <span aria-hidden="true">×</span>
        </button>
        <h4>Your name and date of birth do not match our records</h4>
        <p>Your votes have not been cast. Please try again.</p>
    </div>
    <div class="center-panel">
		<h5>Past John Jarrold Runner of the Month winners</h5>
        <table
            class="table table-striped table-bordered"
            id="runner-of-the-month-winners-table">
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
            <tfoot>
                <tr>
                    <th>Year</th>
                    <th>Month</th>
                    <th></th>
                    <th>Men</th>
                    <th>Ladies</th>
                    <th>Boys</th>
                    <th>Girls</th>
                </tr>
            </tfoot>
            <tbody></tbody>
        </table>
    </div>
</div>
<script type="text/javascript" src="<?php echo plugins_url('/lib/jquery.typeahead.min.js', dirname(__FILE__) ); ?>"></script>
<script type="text/javascript" src="<?php echo plugins_url('/lib/bootstrap-datepicker.min.js', dirname(__FILE__) ); ?>"></script>
<link rel='stylesheet' id='bootstrap-datepicker3-standalone-css'  href='<?php echo plugins_url('/lib/bootstrap-datepicker3.standalone.min.css', dirname(__FILE__) ); ?>' type='text/css' media='all' />
<link rel='stylesheet' id='jquery-typeahead-css'  href='<?php echo plugins_url('/lib/jquery.typeahead.min.css', dirname(__FILE__) ); ?>' type='text/css' media='all' />
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
	  
	  // var runners;
	  function getRunners() {			
			$.getJSON( '<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/runners' )
				.done(function( data ) {
					autoCompleteYourName(data);
					autoCompleteMaleVote(data);
					autoCompleteFemaleVote(data);
				});
		}
	
	function autoCompleteYourName(data) {
		$.typeahead({
			input: '#yourName',
			minLength: 2,
			order: "asc",
			hint: true,
			source: {			 
				data: data
			},
			display: ["name"],
			callback: {
				onClick: function (node, a, item, event) {
					voterId = item.id;				
				}
			}
		});
	}
	
	function autoCompleteMaleVote(data) {
		$.typeahead({
			input: '#menVote',
			minLength: 2,
			order: "asc",
			hint: true,
			source: {			 
				data: data,
				matcher: function (item, displayKey) {
					return item.sexId == 3 ? undefined : true;				
				}
			},
			display: ["name"],			
			callback: {
				onClick: function (node, a, item, event) {
					maleNominationId = item.id;		
				}
			}
		});
	}
	
	function autoCompleteFemaleVote(data) {
		$.typeahead({
			input: '#ladiesVote',
			minLength: 2,
			order: "asc",
			hint: true,
			source: {			 
				data: data,
				matcher: function (item, displayKey) {
					return item.sexId == 2 ? undefined : true;				
				}
			},
			display: ["name"],
			callback: {
				onClick: function (node, a, item, event) {
					femaleNominationId = item.id;
				}
			}
		});
	}
		  
	$('#yourDob').datepicker({        
		format: "yyyy-mm-dd",
		startDate: "1920-01-01",
		endDate: "0d"       ,
        startView: 2,
        maxViewMode: 2,
		autoclose: true
    });
	
	var maleNominationId = 0;
	var femaleNominationId = 0;
	var voterId = 0;
	
	function validateRequest() {
		var success = true;
		if (voterId === undefined || voterId == 0) {
			$('#yourName').parents('div.form-group').first().addClass( "has-error" );
			success = false;
		}
		
		if ($('#yourDob').val() == ''){
			$('#yourDob').parents('div.form-group').first().addClass( "has-error" );
			success = false;
		}
		
		if (maleNominationId > 0 && $('#menVoteReason').val() == '') {
			$('#menVoteReason').parents('div.form-group').first().addClass( "has-error" );
			success = false;
		}
			
		
		if (femaleNominationId > 0 && $('#ladiesVoteReason').val() == '') {
			$('#ladiesVoteReason').parents('div.form-group').first().addClass( "has-error" );
			success = false;
		}
		
		if ((maleNominationId === undefined || maleNominationId == 0)  &&
		    (femaleNominationId === undefined || femaleNominationId == 0)) {
			$('#ladiesVoteReason').parents('div.form-group').first().addClass( "has-error" );
			$('#menVoteReason').parents('div.form-group').first().addClass( "has-error" );
			success = false;
		}
    
    if ($('#voteMonthAndYear') != undefined && $('#voteMonthAndYear').val() == '') {
      $('#voteMonthAndYear').parents('div.form-group').first().addClass( "has-error" );
			success = false;
    }
		
		return success;
	}
	
	 $("#nominate").click(function(e) {
		e.preventDefault();
		$('#alert-ok').addClass('hidden');
		$('#alert-authentication-failed').addClass('hidden');
		
	    var success = validateRequest();
		
		if (!success)
			return;

    var date = $('#voteMonthAndYear').val().split('-');
		var votes = { 				
			voterId: voterId,
			voterDateOfBirth: $('#yourDob').val(),
      month: date[1],
      year: date[0]
		};
		
		if (maleNominationId > 0) {
			votes.men = {
				runnerId: maleNominationId,
				reason: $('#menVoteReason').val()
			}
		}
		
		if (femaleNominationId > 0) {
			votes.ladies = {
				runnerId: femaleNominationId,
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
				maleNominationId = 0;
				femaleNominationId = 0;
				$('#runnerOfTheMonthVote .has-error').removeClass('has-error');				
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
	 });	
	});
</script>


		  