/* **
	searchBar object
	Search throught html5 DOM objects text. Like a filter.

	NEED : Jquery

	Author : Deloffre Cyril
	Website : http://neospiro.fr
*/
var SearchBar = {
	initialize : function(searchInput, contentFilter){
		this.content = $(contentFilter);
		$(searchInput).change(this.cbSearch())
			.keyup(this.cbSearch());
	},
	find : function(searchRequest){
		var words = searchRequest.split(" ");
		$(this.content).css("display","none");
		for(aWord in words){
			$(this.content).each(function (aWord) {
    			if($(this).text().toUpperCase().search(aWord.toUpperCase())!==-1){
    				$(this).css("display","block");
    			}
			},[ words[aWord] ]);
		}	
	},

	//callBack
	cbSearch : function(){
		return function(event) {
			SearchBar.find($(this).val());
		};
	}

}