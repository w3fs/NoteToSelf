/**
 * NoteToSelf object
 */
var NoteToSelf = {
    /**
     * Returns localStorage object. If it does not exist, create it.
     * @returns object
     */
    getStorage: function(){
        var storage = JSON.parse(localStorage.getItem('NoteToSelf')) || localStorage.setItem('NoteToSelf',JSON.stringify([]));
        return storage;
    },
    /**
     * Add note to localStorage
     * @param string note
     */
    add: function(note){
        var store = this.getStorage();
        var len = store.length || 0;
        store[len] = note;
        localStorage.setItem('NoteToSelf', JSON.stringify(store));
    },
    /**
     * Returns number of items in storage
     * @returns number
     */
    count: function(){
        return (this.getStorage()).length;
    },
    /**
     * Remove a note from storage and from the DOM
     * @param  number id
     */
    remove: function(id){
        var store = this.getStorage();
        store.splice(id,1);
        localStorage.setItem('NoteToSelf', JSON.stringify(store));
    }
};
