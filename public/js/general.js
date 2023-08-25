/* Document Ready */
$(document).ready(function() {
    equalHeight();
    setTimeout(function (){
        $("table#indexDataTable").parent().addClass('table-responsive');
    }, 10);

    $(document).on('click',".dataTables_paginate",function(){
         $("html, body").animate({ scrollTop: 0 }, "fast");
        // window.scrollTo({ top: 0, behavior: "smooth" });
    });
    /* On scroll small header */

	// $(window).scroll(function(e) {
	// 	if($(window).scrollTop() > 0){
	// 		$(".layout-wrapper").addClass('sticky-header');

	// }	else{
	// 		$(".layout-wrapper").removeClass('sticky-header');

	// 	}
	// });
    // Custom Choosen file name
    $(document).on("change","input[type=file]",function(e) {
        $('.choosen-file').html('');
        var selectedFileName = e.target.files[0].name;
        console.log(selectedFileName + ' is the selected file .');
        $("<span class='selected-file-text'>Selected File:</span><h6 class='ms-2 d-inline-block'>" + selectedFileName + "</h6>").appendTo(".choosen-file");
    });

    // Set comment area height
    var projectActivityCardHeight = $(".profile-details .profile-view").outerHeight();
    $(".profile-details .profile-comment-view").css({"max-height": projectActivityCardHeight,});

    // Add custom scrollbar for project activity comments
    if($('.project-activity_card').length > 0) {
        new SimpleBar($('.project-activity_card .show_comment')[0]);
    }
    // new SimpleBar($('.custom-table-scrollbar #datatable')[0]);
    if($(`.profile-details`).length > 0) {
        new SimpleBar($(".profile-details .profile-comment-view")[0]);
    }

    $("a[href='#']").click(function(e) {
		e.preventDefault();
	});
    
});
/* Equal Height */
function equalHeight() {
	$.fn.extend({
		equalHeights: function() {
			var top = 0;
			var row = [];
			var classname = ('equalHeights' + Math.random()).replace('.', '');
			$(this).each(function() {
				var thistop = $(this).offset().top;
				if (thistop > top) {
					$('.' + classname).removeClass(classname);
					top = thistop;
				}
				$(this).addClass(classname);
				$(this).height('auto');
				var h = (Math.max.apply(null, $('.' + classname).map(function() {
					return $(this).outerHeight();
				}).get()));
				$('.' + classname).height(h);
			}).removeClass(classname);
		}
	});
	$('.classname').equalHeights();
	$('.event-card .card-title').equalHeights();
}
/* Window Resize */
$(window).resize(function() {
    equalHeight();
    // Set comment area height
    var projectActivityCardHeight = $(
        ".profile-details .profile-view"
    ).outerHeight();
    $(".profile-details .profile-comment-view").css({
        "max-height": projectActivityCardHeight,
    });
});


/* Window Load */
$(window).on("load",function() {
    equalHeight();
});

// for get user designation and team
function getUserDesigTeam(userId) {
    $("#designation_id").val("").trigger('change.select2');
    $("#team_id").val("").trigger('change.select2');
    if(userId > 0) {
        $.ajax({
            url: getUserDesigTeamUrl,
            data: {
                user_id: userId,
            },
            success: function (response) {
                if(response.data && response.data.user_designation) {
                    $("#designation_id").val(response.data.designation_id).trigger('change.select2');
                }
                if(response.data && response.data.user_team) {
                    $("#team_id").val(response.data.team_id).trigger('change.select2');
                }
            },
            error: function (response) {
                toastr.error(SometingWentWrong);
            },
        });
    }
}


$(document).ready(function() {

    CKEDITOR.replaceAll( function( textarea, config ) {
        if ( new CKEDITOR.dom.element( textarea ).hasClass( 'init-ck-editor' ) ) {
            CKEDITOR.tools.extend( config, {
                toolbar: [{
                        name: 'basicstyles',
                        groups: ['basicstyles', 'cleanup'],
                        items: ['Bold', 'Italic', 'Underline', ]
                    },
                    {
                        name: 'paragraph',
                        groups: ['list'],
                        items: ['BulletedList']
                    },
                    {
                        name: 'links',
                        items: ['Link', 'Unlink']
                    },
                ]
            } );
            return true;
        }
        return false;
    } );
    

});
