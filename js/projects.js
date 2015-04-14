$(function(){
	console.log( "jquery ready" );

	//Variables
	var resizetextareas_to 	= null;
	var savechanges_to 		= null;
	var updatethumbnail_to  = null;
	var dragging_el 		= null;
	var saving				= false;

	var data 				= [];
	var unsaveddata 		= [];
	var savingdata 			= [];
	var project_els			= [];

	var image_directories 	= {"thumbnail_image":"/img/project_thumbnails/", "client_logo":"/img/client_logos/"};


	//Handlers
	$("a.save-button").click( initsavechanges );
	$("a.add-toggle-button").click( togglenewprojectform );
	$("a.add-button").click( initaddproject );
	$("a.edit-modules-button").click(function(){
		var project_id = $(this).data("project-id");
		window.location.href = base_url + "projects/modules/" + project_id;
	});
	$('input[name=datetime]').appendDtpicker();
	$('input[name=datetime]').change(updateTime);


	// CHANGE PUBLISH TO DRAFT
	$('.unchecked-btn, .checked-btn').click(function(){
		$(this).addClass('checked-btn');
		$(this).removeClass('unchecked-btn');
		$(this).siblings().addClass('unchecked-btn');
		$(this).siblings().removeClass('checked-btn');

		
		var data_publish = $(this).data("publish");
		
		var pjid = $(this).data("project-id");
		var data = {
			datapublish: data_publish,
			project_id: pjid
		};

		console.log(data);
		$.ajax({
	        type: 'POST',
	        url: base_url + "projects/togglePublish",
	        data: data,
	        dataType: "text",
	        success: function( data ) {
	            console.log( data );
	        }
	    });

	});

	//Methods
	function populatetextareadata(){
		$( ".project" ).each(function(){
			initializeproject( $(this) );
		});
	}

	function initializeproject( _el ){
		var project_id = _el.data("id"), project_data = [];

		_el.find( "textarea" ).each( function(){
			var ta = $( this );
			var col_name = ta.data( "column-name" );
			var isthumnail = ta.hasClass( "thumbnail" );

			project_data[ col_name ] 	= $( this ).val();

			$( this ).on( "keyup" , function(){
				var new_val = $( this ).val();

				if( data[ project_id ][ col_name ] != new_val ){
					if( unsaveddata[ project_id ] == undefined ) unsaveddata[ project_id ] = {};

					unsaveddata[ project_id ][ col_name ] = new_val;

					//console.log( unsaveddata[ project_id ][ col_name ] != null ? "updating unsaved data: " : "adding unsaved data: ", project_id, col_name );
					//console.log( unsaveddata );
				} else {
					delete unsaveddata[ project_id ][ col_name ];
					if( Object.keys( unsaveddata[ project_id ] ).length == 0 ) delete unsaveddata[ project_id ];

					//console.log( "removing unsaved data: ", project_id, col_name );
					//console.log( unsaveddata );
				}

				checkforunsavedchanges();

				if(isthumnail) initupdatethumbnail( ta, col_name );
			});
		});

		_el.find( ".delete-button" ).eq( 0 ).on( "click" , function(){
			initdeleteproject( project_id );
		});

		project_els[ project_id ] 	= _el;
		data[ project_id ] 			= project_data;
	}

	function initupdatethumbnail( _ta, _col_name ){
		clearTimeout(updatethumbnail_to);

		updatethumbnail_to = setTimeout( updatethumbnail, 1000, _ta, _col_name );
	}

	function updatethumbnail( _ta, _col_name ){
		console.log("updating thumbnail");

		var img = _ta.parent().parent().children("img")[0];
		var img_directory = image_directories[ _col_name ];
		img.src = "http://" + client_domain + img_directory + _ta.val() + ".jpg";
	}

	function updateTime() {
		console.log('date was changed');
		// data = $(this).val();
		var data = {
			timedata: $(this).val(),
			projectid: $(this).data("project-id")
		};

		// $(this).attr('value', $(this).val());

		$.ajax({
	        type: 'POST',
	        url: base_url + "projects/updateTime",
	        data: data,
	        dataType: "text",
	        success: function( data ) {
	            console.log( data );
	        }
	    });
		
	}

	function togglenewprojectform(){
		$( ".toolbar" ).toggleClass( "add-project-open" );
		initresizetextareas();
	}

	function checkforunsavedchanges(){
		if( Object.keys(unsaveddata).length > 0 ){
			tounsavedchanges();
		} else {
			tonochangesstate();
		}
	}

	function tounsavedchanges(){
		if( !$( ".toolbar" ).hasClass( "unsaved-changes" ) )
			$( ".toolbar" ).addClass( "unsaved-changes" );
	}

	function tonochangesstate(){
		$( ".toolbar" ).removeClass( "unsaved-changes" );
	}

	function initresizetextareas(){
		clearTimeout( resizetextareas );

		setTimeout( resizetextareas, 100 );
	}
	function resizetextareas(){
		console.log( "resizetextareas" );

		$( "textarea" ).each( function(){
			if( $(this).data("autosize") ){
				$(this).resize();
			} else {
				$(this).autosize();
			}
		});

		$( ".projects-container" ).stop().delay( 100 ).animate( { "opacity": 1 } );
	}

	function initaddproject(){
		if( !saving ){
			console.log( "adding project" );

			clearTimeout( savechanges_to );

			savingdata = $("#addproject-form").serialize();

			disable();

			saving = true;

			savechanges_to = setTimeout( addproject, 100 );
		} else {
			console.log( "busy..." );
		}
	}
	function addproject(){
		$.ajax({
			type:"POST",
			url: base_url + "projects/add",
			data: savingdata,
		    dataType: "html",
	        success: function( reponse ) {
	            console.log( 'Add project success: ' );
	            console.log( reponse );

	            //add the html
	            $("ul.projects-list").prepend( reponse );
	            initializeproject( $(".project").eq(0) );
	            resizetextareas();

	            setTimeout( addprojectcomplete, 200 );
	        },
	        error: function( e ) 
	        {
	            console.log( 'Error: ' + e );
	            console.log( e );

	            setTimeout( addprojectfailed, 200 );
	        }
		});
	}
	function addprojectcomplete(){
		console.log("addprojectcomplete");
		console.log(data);

		saving = false;
		enable();

		togglenewprojectform();
	}
	function addprojectfailed(){
		saving = false;
		enable();
	}

	function initsavechanges(){
		if( !saving && Object.keys( unsaveddata ).length > 0 ){
			clearTimeout( savechanges_to );

			disable();

			saving = true;

			savingdata = [];

			$.each( Object.keys( unsaveddata ), function( i, project_id ){
				console.log("saving project id: ", project_id);

				savingdata.push({ id:project_id, data:unsaveddata[ project_id ] });

				$.each( Object.keys( unsaveddata[ project_id ] ), function( ii, col_name ){
					console.log("-- column name: ", col_name);
				});
			});

			savechanges_to = setTimeout( savechanges, 100 );
		} else {
			console.log("busy...");
		}
	}
	function savechanges(){
		console.log( "saving changes..." );

		$.ajax({
			type:"POST",
			url: base_url + "projects/update",
			data:{data:savingdata},
		    dataType: "text",
	        success: function(reponse) {
	            console.log('Save changes success: ');
	            console.log(reponse);

	            setTimeout( savechangescomplete, 200 );
	        },
	        error: function(e) 
	        {
	            console.log('Error: ' + e);
	            console.log(e);

	            setTimeout( savechangesfailed, 200 );
	        }
		});
	}
	function savechangesfailed(){
		console.log( "saving changes failed" );

		saving = false;

		checkforunsavedchanges();
		enable();	
	}
	function savechangescomplete(){
		console.log( "saving changes complete" );

		//update the data array with saved values
		$.each( Object.keys( unsaveddata ), function( i, project_id ){
			$.each( Object.keys( unsaveddata[ project_id ] ), function( ii, col_name ){
				data[ project_id ][ col_name ] = unsaveddata[ project_id ][ col_name ];
			});
		});
		unsaveddata = [];

		saving = false;

		checkforunsavedchanges();
		enable();	
	}

	function initdeleteproject( _project_id ){
		if( !saving ){
			console.log("deleting project", _project_id);

			var verify = confirm("Are you sure you want to delete this project? This cannot be undone.");
			if (verify == true) {
			    clearTimeout( savechanges_to );

				disable();

				savingdata = _project_id;

				saving = true;

				savechanges_to = setTimeout( deleteproject, 100 );
			} else {
			    console.log("Delete project request cancelled.");
			}
		} else {
			console.log("busy...");
		}
	}
	function deleteproject(){
		$.ajax({
			type:"POST",
			url: base_url + "projects/delete",
			data:{ id: savingdata },
		    dataType: "text",
	        success: function(reponse) {
	            console.log('Delete project success: ');
	            console.log(reponse);

	            setTimeout( deleteprojectcomplete, 200 );
	        },
	        error: function(e) 
	        {
	            console.log('Error: ' + e);
	            console.log(e);

	            setTimeout( deleteprojectfailed, 200 );
	        }
		});
	}
	function deleteprojectcomplete(){
		//remove the li
		project_els[ savingdata ].parent("li").remove();

		//remove project from the data array
		delete data[ savingdata ];

		//remove project el from the els array
		delete project_els[ savingdata ];

		saving = false;
		enable();
	}
	function deleteprojectfailed(){
		savingdata = [];

		saving = false;
		enable();
	}

	function disable(){
		$( "textarea" ).attr( "disabled", true );
	}

	function enable(){
		$( "textarea" ).attr( "disabled", false );
	}

	//Init
	initresizetextareas();
	populatetextareadata();
});

// $( ".view-toggle" ).on( "change", function(){
// 	$( ".projects-container" ).css( "opacity", 0 );
// 	$( ".projects-container" ).toggleClass( "list-view" );

// 	initresizetextareas();
// });

// $( ".project-title" ).click( function(){
// 	$( this.parent ).toggleClass( "open" );
// });

// $( "li.project-wrapper" ).on( "dragstart", function(e){
// 	dragging_el = e.currentTarget;
// });

// $( "li.project-wrapper" ).on( "dragover", function(e){
// 	e.preventDefault();

// 	var el = e.currentTarget;

// 	//remove drag over from all other than this one
// 	$( ".dragover" ).each( function(){ 
// 		if( this != el ) $(this).removeClass( "dragover" );
// 	});

// 	if( el != dragging_el && !$(el).hasClass( "dragover" ) ) 
// 		$(el).addClass( "dragover" );
// });