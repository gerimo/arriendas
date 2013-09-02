$(document).ready(function() {

        
	  $( ".datepicker" ).datepicker({
			dateFormat: 'yy-mm-dd', 
	//	buttonImageOnly: true,
		//minDate:'-0d'
	  });

	$("#notenddate").change( function () 
	{
		checksAndRadios();		
	});
	
	
	$("input:radio[name=repeatdays]").change( function () 
	{
		checksAndRadios();
	});
	
  function is_int(input){
    return !isNaN(input)&&parseInt(input)==input;
  }
	
	function checksAndRadios()
	{
	
		var radioval = $("input:radio[name=repeatdays]:checked").attr("id");
		
		
		if(radioval == "never")
		{
			$("#notenddate").attr('checked', false);
			$('#days').attr("disabled", true); 
			$('#days').val('');
		}
		else if(!$("#notenddate").attr('checked'))
		{
			//alert("desactivado");
			$('#days').removeAttr("disabled"); 
			
		}
		else
		{
			//alert("activado");
			$('#days').attr("disabled", true); 
			$('#days').val('');
		}  
	
	}
	
	
	
	$('#date').change(function() {
		putday($('#date').val());
	});
	
	
	function putday(valor)
	{
		var d=new Date(valor);
		var weekday=new Array(7);
		
		weekday[0]="lunes";
		weekday[1]="martes";
		weekday[2]="miercoles";
		weekday[3]="jueves";
		weekday[4]="viernes";
		weekday[5]="sabados";
		weekday[6]="domingo";	

		var n = weekday[d.getDay()];
		
		$('#dayofweek').val(d.getDay());
		$('#daytext').html('Solos los dias ' + n); 
	}

	
	function getCheckedValue(radioObj) {
	if(!radioObj)
		return "";
	var radioLength = radioObj.length;
	if(radioLength == undefined)
		if(radioObj.checked)
			return radioObj.value;
		else
			return "";
	for(var i = 0; i < radioLength; i++) {
		if(radioObj[i].checked) {
			return radioObj[i].value;
		}
	}
	return "";
	}
	
	
	function getCheckedValue(radioObj) {
	if(!radioObj)
		return "";
	var radioLength = radioObj.length;
	if(radioLength == undefined)
		if(radioObj.checked)
			return radioObj.value;
		else
			return "";
	for(var i = 0; i < radioLength; i++) {
		if(radioObj[i].checked) {
			return radioObj[i].value;
		}
	}
	return "";
	}
	
   var $calendar = $('#calendar');
   var id = 10;

   $calendar.weekCalendar({
      displayOddEven:true,
      timeslotsPerHour : 4,
      allowCalEventOverlap : true,
      overlapEventsSeparate: true,
      firstDayOfWeek : 1,
      //businessHours :{start: 8, end: 18, limitDisplay: true },
      daysToShow : 7,
      switchDisplay: {'1 d&iacute;a': 1, '3 d&iacute;as': 3, 'Toda la semana': 7},
      title: function(daysToShow) {
			return daysToShow == 1 ? '%date%' : '%start% - %end%';
      },
      height : function($calendar) {
         return $(window).height() - $("h1").outerHeight() - 1;
      },
      eventRender : function(calEvent, $event) {
         if (calEvent.end.getTime() < new Date().getTime()) {
            $event.css("backgroundColor", "#aaa");
            $event.find(".wc-time").css({
               "backgroundColor" : "#999",
               "border" : "1px solid #888"
            });
         }
      },
      draggable : function(calEvent, $event) {
         //return calEvent.readOnly != true;
		 return false;
      },
      resizable : function(calEvent, $event) {
         return calEvent.readOnly != true;
      },
      eventNew : function(calEvent, $event) {
         var dialogContent = $("#event_edit_container");
         resetForm(dialogContent);
         var startField = dialogContent.find("select[name='start']").val(calEvent.start);
         var endField = dialogContent.find("select[name='end']").val(calEvent.end);
         var titleField = dialogContent.find("input[name='title']");
         var bodyField = dialogContent.find("textarea[name='body']");
		 var dateField = dialogContent.find("input[name='date']").val(calEvent.start.toString('yyyy-MM-dd'));
		 var daysField;
		 var carField;
			
		carField = dialogContent.find("select[name='car']");
		daysField = dialogContent.find("input[name='days']").val("");

		$('#days').attr("disabled", true); 
		dialogContent.find("input:radio[name=repeatdays]:nth(0)").attr('checked',true);
		
		 putday($('#date').val());
	

         dialogContent.dialog({
            modal: true,
            title: "Definir Disponibilidad",
            close: function() {
               dialogContent.dialog("destroy");
               dialogContent.hide();
               $('#calendar').weekCalendar("removeUnsavedEvents");
            },
            buttons: {
               Guardar : function() {
			   
			   
							if(	$('#days').attr("disabled") == undefined && !is_int( $('#days').val()))
							{
								alert("Ingrese una cantidad de dias");
								
							}
							else
							{	
								
								if(calEvent.id == undefined)
										calEvent.id = "";	
				   
								var start = new Date(startField.val());
								var end = new Date(endField.val()); 
								
								//document.location = '<?= url_for("profile/saveEvent") ?>'	 + '?id='+ calEvent.id + '&date=' + dateField.val() + '&carid=' + carField.val() + '&days='+ daysField.val() +	'&start=' + start.toString('HH:mm:ss') + '&end='+ end.toString('HH:mm:ss') + '&repeat=' + dialogContent.find("input:radio[name=repeatdays]:checked").attr("id") +'&forever='+ dialogContent.find("#notenddate").attr('checked') +'&dayofweek=' + $('#dayofweek').val();
								
								//document.write('<?= url_for("profile/saveEvent") ?>'	 + '?id='+ calEvent.id + '&date=' + dateField.val() + '&carid=' + carField.val() + '&days='+ daysField.val() +	'&start=' + start.toString('HH:mm:ss') + '&end='+ end.toString('HH:mm:ss') + '&repeat=' + dialogContent.find("input:radio[name=repeatdays]:checked").attr("id") +'&forever='+ dialogContent.find("#notenddate").attr('checked') +'&dayofweek=' + $('#dayofweek').val());
								
							
								$.getJSON('<?= url_for("profile/saveEvent") ?>',
								{ 
									'id': calEvent.id,
									'date': dateField.val(),
									'carid': carField.val(),
									'days': daysField.val(),
									'start': start.toString('HH:mm:ss'),
									'end': end.toString('HH:mm:ss'),
									'repeat' : dialogContent.find("input:radio[name=repeatdays]:checked").attr("id"),
									'forever' : dialogContent.find("#notenddate").attr('checked'),
									'dayofweek' : $('#dayofweek').val()
								}, 
								function(ret){ 
								  
								  //alert(ret[0].start);
								  
								  $('#calendar').weekCalendar("clear");
								  $('#calendar').weekCalendar("refresh");
								  dialogContent.dialog("close");
								  
								
								});
							}
                
               },
               Cancelar : function() {
                  dialogContent.dialog("close");
               }
            }
         }).show();

         dialogContent.find(".date_holder").text($calendar.weekCalendar("formatDate", calEvent.start));
         setupStartAndEndTimeFields(startField, endField, calEvent, $calendar.weekCalendar("getTimeslotTimes", calEvent.start));

      },
      eventDrop : function(calEvent, $event) {
        
      },
      eventResize : function(calEvent, $event) {
      },
      eventClick : function(calEvent, $event) {

         if (calEvent.readOnly) {
            return;
         }

         var dialogContent = $("#event_edit_container");
         resetForm(dialogContent);
         var startField; // = dialogContent.find("select[name='start']").val(calEvent.start);
         var endField; //= dialogContent.find("select[name='end']").val(calEvent.end);
         var titleField = dialogContent.find("input[name='title']").val(calEvent.title);
         var bodyField = dialogContent.find("textarea[name='body']").val(calEvent.body);
         //var checkField = dialogContent.find("textarea[name='body']").val(calEvent.body);
		 var dateField; //= dialogContent.find("input[name='date']").val(calEvent.start.toString('yyyy-MM-dd'));
		 var daysField;
		 var carField;

	 
				$.getJSON('<?= url_for("profile/getAvInfo") ?>',
					{ 
						'id':calEvent.id 
						
					}, 
					function(ret){ 
					
						calEvent.id = ret[0].id;

						var start = new Date(ret[0].datefrom +" " + ret[0].start);
						var end = new Date(ret[0].datefrom +" " + ret[0].end);
						
						calEvent.start = start;
						calEvent.end = end;
						
						dateField = dialogContent.find("input[name='date']").val(ret[0].datefrom);
						carField = dialogContent.find("select[name='car']").val(ret[0].carid);
						daysField = dialogContent.find("input[name='days']").val(ret[0].repeat);
						startField = dialogContent.find("select[name='start']").val(start);
						endField = dialogContent.find("select[name='end']").val(end);
						
						setupStartAndEndTimeFields(startField, endField, calEvent, $calendar.weekCalendar("getTimeslotTimes", calEvent.start));
						
							
						
						//RADIOS
						
						if(ret[0].datefrom == ret[0].dateto)
						{
							$('#days').attr("disabled", true); 
							$('#days').val('');
							dialogContent.find("input:radio[name=repeatdays]:nth(0)").attr('checked',true);
						}
						else if(ret[0].day  == null)
						{ 
							$('#days').removeAttr("disabled"); 
							dialogContent.find("input:radio[name=repeatdays]:nth(1)").attr('checked',true);
						}
						else if(ret[0].day  != null)
						{
							$('#days').removeAttr("disabled"); 
							dialogContent.find("input:radio[name=repeatdays]:nth(2)").attr('checked',true);
						}
						
						//CHECK
												
						if(ret[0].dateto.toString() == "1970-01-01")
						{
							
							$('#days').attr("disabled", true);
							$('#days').val('');
							$("#notenddate").attr('checked', true);
							
						}
					
						putday($('#date').val());
						
					} 
				); 


				 dialogContent.dialog({
					modal: true,
					title: "Editar - " + calEvent.title,
					close: function() {
					   dialogContent.dialog("destroy");
					   dialogContent.hide();
					   $('#calendar').weekCalendar("removeUnsavedEvents");
					},
					buttons: {
					   Guardar : function() {
					
					   		
							if(	$('#days').attr("disabled") == undefined && !is_int( $('#days').val()))
							{
								alert("Ingrese una cantidad de dias");
								
							}
							else
							{	
								   //alert(dialogContent.find("input:radio[name=repeatdays]:checked").attr("id"));
								   //alert(dialogContent.find("#notenddate").attr('checked'));
								 
									var start = new Date(startField.val());
									var end = new Date(endField.val()); 	
									
									$.getJSON('<?= url_for("profile/saveEvent") ?>',
									{ 
									
										'id': calEvent.id,
										'date': dateField.val(),
										'carid': carField.val(),
										'days': daysField.val(),
										'start': start.toString('HH:mm:ss'),
										'end': end.toString('HH:mm:ss'),
										'repeat' : dialogContent.find("input:radio[name=repeatdays]:checked").attr("id"),
										'forever' : dialogContent.find("#notenddate").attr('checked'),
										'dayofweek' : $('#dayofweek').val()
									}, 
									function(ret){ 
									 
									  //calEvent.id = ret[0].id;

										 $('#calendar').weekCalendar("clear");
										 $('#calendar').weekCalendar("refresh");
										  dialogContent.dialog("close");
									  
									 // $calendar.weekCalendar("updateEvent", calEvent);
									 // dialogContent.dialog("close");
									
									});
							}
				
					   },
					   "delete" : function() {
					   
							$.getJSON('<?= url_for("profile/deleteEvent") ?>',
							{ 
							
								'id': calEvent.id,
							}, 
							function(ret){ 
							 
								 $('#calendar').weekCalendar("clear");
								 $('#calendar').weekCalendar("refresh");
								  dialogContent.dialog("close");
							 
							});
							
						  //$calendar.weekCalendar("removeEvent", calEvent.id);
						  //dialogContent.dialog("close");
					   },
					   Cancelar : function() {
						  dialogContent.dialog("close");
					   }
					}
				 }).show();
				 
		 
		 

         //var startField = dialogContent.find("select[name='start']").val(calEvent.start);
         //var endField = dialogContent.find("select[name='end']").val(calEvent.end);
         //dialogContent.find(".date_holder").text($calendar.weekCalendar("formatDate", calEvent.start));
         //setupStartAndEndTimeFields(startField, endField, calEvent, $calendar.weekCalendar("getTimeslotTimes", calEvent.start));
         $(window).resize().resize(); //fixes a bug in modal overlay size ??

      },
      eventMouseover : function(calEvent, $event) {
      },
      eventMouseout : function(calEvent, $event) {
      },
      noEvents : function() {

      },
      data : function(start, end, callback) {
			
			$.getJSON("<?= url_for('profile/ajaxCalendar') ?>", {
				start: start.toString('yyyy-MM-dd'),
				end: end.toString('yyyy-MM-dd')
			},  function(result) {

					callback(result);			
				});
		   }

   });

   function resetForm(dialogContent) {
	  dialogContent.find("input:radio[name=repeatdays]").attr('checked',false);
      dialogContent.find("input").val("");
      dialogContent.find("textarea").val("");
   }


   /*
    * Sets up the start and end time fields in the calendar event
    * form for editing based on the calendar event being edited
    */
   function setupStartAndEndTimeFields($startTimeField, $endTimeField, calEvent, timeslotTimes) {

      $startTimeField.empty();
      $endTimeField.empty();

      for (var i = 0; i < timeslotTimes.length; i++) {
         var startTime = timeslotTimes[i].start;
         var endTime = timeslotTimes[i].end;
         var startSelected = "";
         if (startTime.getTime() === calEvent.start.getTime()) {
            startSelected = "selected=\"selected\"";
         }
         var endSelected = "";
         if (endTime.getTime() === calEvent.end.getTime()) {
            endSelected = "selected=\"selected\"";
         }
         $startTimeField.append("<option value=\"" + startTime + "\" " + startSelected + ">" + timeslotTimes[i].startFormatted + "</option>");
         $endTimeField.append("<option value=\"" + endTime + "\" " + endSelected + ">" + timeslotTimes[i].endFormatted + "</option>");

         $timestampsOfOptions.start[timeslotTimes[i].startFormatted] = startTime.getTime();
         $timestampsOfOptions.end[timeslotTimes[i].endFormatted] = endTime.getTime();

      }
      $endTimeOptions = $endTimeField.find("option");
      $startTimeField.trigger("change");
   }

   var $endTimeField = $("select[name='end']");
   var $endTimeOptions = $endTimeField.find("option");
   var $timestampsOfOptions = {start:[],end:[]};

   //reduces the end time options to be only after the start time options.
   $("select[name='start']").change(function() {
      var startTime = $timestampsOfOptions.start[$(this).find(":selected").text()];
      var currentEndTime = $endTimeField.find("option:selected").val();
      $endTimeField.html(
            $endTimeOptions.filter(function() {
               return startTime < $timestampsOfOptions.end[$(this).text()];
            })
            );

      var endTimeSelected = false;
      $endTimeField.find("option").each(function() {
         if ($(this).val() === currentEndTime) {
            $(this).attr("selected", "selected");
            endTimeSelected = true;
            return false;
         }
      });

      if (!endTimeSelected) {
         //automatically select an end date 2 slots away.
         $endTimeField.find("option:eq(1)").attr("selected", "selected");
      }

   });


   var $about = $("#about");

   $("#about_button").click(function() {
      $about.dialog({
         title: "About this calendar demo",
         width: 600,
         close: function() {
            $about.dialog("destroy");
            $about.hide();
         },
         buttons: {
            close : function() {
               $about.dialog("close");
            }
         }
      }).show();
   });


});
