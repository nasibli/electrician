function ResultApi () {
        
    this.add = function (userName) {
        return $.post('/result', {userName: userName} );
    }
    
    this.getBest = function() {
        return $.get('/result');
    }
    
}
