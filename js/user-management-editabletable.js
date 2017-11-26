/*global $, window*/
// for changing td to editable cell
var hasChangeWord;
$.fn.editableTableWidget = function (options) {
	'use strict';

	return $(this).each(function () {
		
		function bindEvents(){
			hasChangeWord=0;
			// event occur when element loses focus
			editor.one('blur',function () {
				// console.log('hasChangeWord:'+hasChangeWord);
				if (hasChangeWord==1){
					setActiveText();
				}
				editor.hide();
			}).keydown(function (e) { // different operation on cells by keyboard
				if (e.which === ENTER) {
					setActiveText();
					editor.hide();
					active.focus();
					e.preventDefault();
					e.stopPropagation();
				} else if (e.which === ESC) {
					editor.val(active.text());
					e.preventDefault();
					e.stopPropagation();
					editor.hide();
					active.focus();
				} else if (e.which === TAB) {
					active.focus();
				} else if (this.selectionEnd - this.selectionStart === this.value.length) {
					var possibleMove = movement(active, e.which);
					if (possibleMove.length > 0) {
						possibleMove.focus();
						e.preventDefault();
						e.stopPropagation();
					}
				}
			})
			.on('input paste', function () {
				hasChangeWord=1;
				var evt = $.Event('validate');
				active.trigger(evt, editor.val());
				// console.log('result:'+evt.result);
				if (evt.result === false) {
					editor.addClass('error');
				} else {
					editor.removeClass('error');
				}
			});
		}

		var buildDefaultOptions = function () {
				// extend: merge content of two object to first object
				var opts = $.extend({}, $.fn.editableTableWidget.defaultOptions);
				opts.editorText = opts.editorText.clone();
				opts.editorSelect = opts.editorSelect.clone();
				return opts;
			},
			activeOptions = $.extend(buildDefaultOptions(), options),
			ARROW_LEFT = 37, ARROW_UP = 38, ARROW_RIGHT = 39, ARROW_DOWN = 40, ENTER = 13, ESC = 27, TAB = 9,
			element = $(this),
			editor,
			editorText = activeOptions.editorText.css('position', 'absolute').hide().appendTo(element.parent()),
			editorSelect = activeOptions.editorSelect.css('position','absolute').hide().appendTo(element.parent()),
			active,
			showEditor = function (select) {
				active = element.find('td:focus');
				// prevent user press columns with no_focus class
				// if (active.index() == "0" || active.index()=="7"){
				// 	return;
				// }
				if (active.hasClass("no_focus")){
					return;
				}
				// allow edting
				if (active.length) {
					if (active.index() == "6" || active.index()==7){
						editorSelect.children('option').remove();
						if (active.index()==6){
							var tempOptions = ["Waiter","Waitress","Cook","Cashier","Manager"];
						} else {
							var tempOptions = ["M","F"];
						}
						var selected = false;

						for (var i=0; i<tempOptions.length; i++){
							if(active.text() === tempOptions[i]){
								selected = true;
							}
							editorSelect.append($('<option value="'+tempOptions[i]+'"'+ (selected?'selected':'') +'>'+ tempOptions[i]+'</options>' ));
						}

						editor = editorSelect.val(active.text())
						.removeClass('error')
						.show()
						.offset(active.offset())	// move editor to correct place
						.css('height',active.outerHeight())
						.css('width',active.outerWidth())
						.focus();

						bindEvents();

					} else {
						// element.find('td:focus').autocomplete({source:['a','b','c']});

						editor = editorText.val(active.text())
						.removeClass('error')
						.show()		// highlight text
						.offset(active.offset())	// move editor to correct place
						.css(active.css(activeOptions.cloneProperties))
						.css('height',active.outerHeight())
						.css('width',active.outerWidth())
						.focus();
						bindEvents();
						if (select) {
							editor.select();
						}
					}

				}
			},
			setActiveText = function () { // after leaving cell
				var text = editor.val(),
					evt = $.Event('change'),
					originalContent;
				// if the change is invalid, recover original content
				if (active.text().trim() === text || editor.hasClass('error')) {
					if (hasChangeWord==1){
						giveAlert(active);
					}
					return true;
				}
				originalContent = active.html(); // replace
				active.text(text).trigger(evt, text);
				if (evt.result === false) { //rare execute
					active.html(originalContent);
				}
			},
			// move by arrow between table cells when not activate
			movement = function (element, keycode) {
				if (keycode === ARROW_RIGHT) {
					return element.next('td');
				} else if (keycode === ARROW_LEFT) {
					return element.prev('td');
				} else if (keycode === ARROW_UP) {
					return element.parent().prev().children().eq(element.index());
				} else if (keycode === ARROW_DOWN) {
					return element.parent().next().children().eq(element.index());
				}
				return [];
			};



		// element.on('click keypress dblclick', showEditor)
		element.on('click keypress dblclick', function(e){
			// console.log("show editor first");
			// console.log(element);
			showEditor(true);	// true: select, false: not select
		})
		.css('cursor', 'pointer')
		.keydown(function (e) {
			var prevent = true,
				possibleMove = movement($(e.target), e.which);
			if (possibleMove.length > 0) {
				possibleMove.focus();
			} else if (e.which === ENTER) {
				showEditor(false);
			} else if (e.which === 17 || e.which === 91 || e.which === 93) {
				showEditor(true);
				prevent = false;
			} else {
				prevent = false;
			}
			if (prevent) {
				e.stopPropagation();
				e.preventDefault();
			}
		});

		$('.no_focus').css('cursor','');

		element.find('td').prop('tabindex', 1);

		$(window).on('resize', function () {
			if (editor && editor.is(':visible')) {
				editor.offset(active.offset())
				.width(active.width())
				.height(active.height());
			}
		});
	});

};
// show default style of edit cell when enter edit mode

$.fn.editableTableWidget.defaultOptions = {
	cloneProperties: ['padding', 'padding-top', 'padding-bottom', 'padding-left', 'padding-right',
					  'text-align', 'font', 'font-size', 'font-family', 'font-weight',
					  'border', 'border-top', 'border-bottom', 'border-left', 'border-right'],
	editorText: $('<input>'),
	// editorSelect: $('<input list="productName">')
	editorSelect: $('<select>')
};

function giveAlert(cell){
	var pos = cell.index();
	// console.log('cell: '+cell.index());
	if (cell.text()=='') {return;}
	if (pos=='3' || pos=='5'){
		alert('Only number is allowed!');
	} else if (pos=='1' || pos=='2') {
		alert('Only alphabet and space are allowed!');
	}
	
}

