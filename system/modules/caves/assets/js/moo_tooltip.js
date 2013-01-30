window.addEvent('domready', function()
{
	var myTips = new Tips($$('.tooltip'),
	{
		showDelay: 0,    //Verzögerung bei MouseOver
		hideDelay: 100,   //Verzögerung bei MouseOut
		className: 'tool', //CSS-Klassennamen --> CSS-Definitionen
		offset: {'x': 20, 'y': -100 }, // Versatz des Tooltips
		// ab MooTools 1.3 wird offset ohne s verwendet.
		fixed: true, // false = Tooltip bewegt sich mit dem Mauszeiger, true=Tooltip bewegt sich nicht
	});

// zeigt den Tooltip bei Fokus an | ergänzt 02.01.2011

	$$('.tooltip').each(function(el) {
		el.addEvent('focus', function(event){
			myTips.elementEnter(event, el);
		}).addEvent('blur', function(event){
			myTips.elementLeave(event, el);
		});
	});
});
