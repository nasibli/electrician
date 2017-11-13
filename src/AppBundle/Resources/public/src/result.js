function Result (api, addWindowId, bestWindowId, bestTableId,  stepId) {
    
    this.api = api;
    this.addWindowId = addWindowId;
    this.bestWindowId = bestWindowId;
    this.bestTableId = bestTableId;
    
    this.stepId = stepId;
    this.onUserAdded = null;
    let result = this;
    
    document.getElementById('btn_save').onclick = save;
    document.getElementById('btn_close').onclick = close;
    document.getElementById('btn_best').onclick = showBest;
    document.getElementById('btn_close_best').onclick = closeBest;
    
    this.onGameCompleted = function (stepCount, onUserAdded) {
        showWindow(stepCount);
        result.onUserAdded = onUserAdded;
    }
    
    function showBest() {
        result.api.getBest().done(function(resp) {
            document.getElementById(result.bestTableId).innerHTML = generateBestList(resp.results);
            $('#'+result.bestWindowId).modal('show');
        });
    }
    
    function showWindow(stepCount) {
        document.getElementById(result.stepId).innerHTML = stepCount;
        $('#'+result.addWindowId).modal('show');
    }
    
    function save() {
        let userName = document.getElementById('input_user').value;
        result.api.add(userName).done(function(resp) {
            if (resp.success) {
                $('#'+result.addWindowId).modal('hide');
                result.onUserAdded();
            }
        })
    }
    
    function close() {
        $('#'+result.addWindowId).modal('hide');
    }
    
    function closeBest() {
        $('#'+result.bestWindowId).modal('hide');
    }
    
    function generateBestList(results) {
        let header = '<tr><th>Пользователь</th><th>Количество ходов</th></tr>';
        let rows = '';
        results.forEach(function(result) {
            rows+= '<tr>' + 
                    '<td>' + result.userName + '</td>' + '<td>' + result.stepCount + '</td>' + 
                    '</tr>';
        });
        return '<table class="table table-bordered table-responsive">' + header + rows + '</table>';
    }
    
}