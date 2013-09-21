/* **
	searchBar object
	Search throught html5 DOM objects text. Like a filter.

	NEED : Jquery

	Author : Deloffre Cyril
	Website : http://neospiro.fr
	Version 0.2

	Updates : 
	 + Search feature
*/
var SearchBar = {
	initialize : function(searchInput, contentFilter)
	{
		this.contentFilter = contentFilter;
		this.searchInput = searchInput;
		//Init callbacks
		$(searchInput).change(this.cbSearch())
			.keyup(this.cbSearch());

		//Implementing new "contains" filter, case insensitive
		jQuery.expr[':'].Contains = function(a, i, m) {
		  return jQuery(a).text().toUpperCase()
		      .indexOf(m[3].toUpperCase()) >= 0;
		};
		return this;
	},
	find : function(searchRequest)
	{
		this.content = $(this.contentFilter);
		if(searchRequest.trim() === "")
		{
			$(this.content)
				.removeClass('SearchBar_find')
				.removeClass('SearchBar_notfind');
			return null;
		}

		var CSSrequest = 
			":Contains('" + 
			searchRequest
				//.replace("'", "\\'") //Not needed Oo
				.trim()
				.split(" ")
				.join(
					"'),:Contains('"	) + 
			"')";
		console.log(CSSrequest);
		$(this.content.filter(CSSrequest))
			.addClass('SearchBar_find')
			.removeClass('SearchBar_notfind');
		$(this.content.filter( ":not(" + CSSrequest + ")" ))
			.addClass('SearchBar_notfind')
			.removeClass('SearchBar_find');
		return this.content.filter(CSSrequest);
	},
	updateContent: function(contentFilter){
		if(typeof(contentFilter)!="undefined")
			this.contentFilter = contentFilter;

		return this.contentFilter;
	},

	
	//callBack
	cbSearch : function(){
		return function(event) {
			SearchBar.find($(this).val());
		};
	}


}