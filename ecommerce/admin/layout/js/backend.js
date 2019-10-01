

        $(function () {

            'use strict';

            // Dashboard


            	$('.toggle-info').click(function () {

            		$(this).toggleClass('selected').parent().next('.panel-body').slideToggle(300);

            		if ($(this).hasClass('selected')) {

            			$(this).html('<i class="fa fa-minus fa-lg"></i>');

            		} else {

            			$(this).html('<i class="fa fa-plus fa-lg"></i>');

            		}

            	});

            // Trigger The Selectboxit

            $("Select").selectBoxIt({

              autoWidth:false

            });

            // Hide Placeholder On Form Focus

            $('[placeholder]').focus(function() {

                $(this).attr('data-text',$(this).attr('placeholder'));

                $(this).attr('placeholder', ' ');

                }).blur(function() {

                $(this).attr('placeholder',$(this).attr('data-text'));


            });

           	// Add Asterisk On Required Field

            $('input').each(function () {

                if ($(this).attr('required') === 'required') {

                    $(this).after('<span class="asterisk">*</span>');

                }

            });

          	// Convert Password Field To Text Field On Hover

                var passField = $('.password');

                $('.show-pass').hover(function () {

                    passField.attr('type', 'text');

                }, function () {

                    passField.attr('type', 'password');

                });

                // confirmation Message On button

                $('.confirm').click(function(){

                        return confirm('Are You Sure ?');
                });

                // Categorey View Opteion

                $('.cat h3').click(function(){

                  $(this).next('.full-view').fadeToggle(200);

                });

                $('.option span').click(function(){

                  $(this).addClass('active').siblings('span').removeClass('active');

                  if($(this).data('view') === 'full') {

                    $('.cat .full-view').fadeIn(300);
                  }else{

                    $('.cat .full-view').fadeOut(300);
                  }

                });



            //  Nice Scroll

            $('html').niceScroll({});

        });
