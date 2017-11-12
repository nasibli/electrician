function Electrician (board, api, boardId, onGameCompleted) {
    
    this.board = board;
    this.api = api;
    this.boardId = boardId;
    this.cellPrefix = 'cell';
    
    let electrician = this;
        
    this.render = function() {
        let boardEl = document.getElementById(this.boardId);
        boardEl.innerHTML = '';
        for (let rowIndex=1; rowIndex <= 5; rowIndex++) {
            for (let colIndex=1; colIndex <= 5; colIndex++) {
                let cell = document.createElement('div');
                cell.setAttribute('class', 'field');
                cell.setAttribute('row-index', rowIndex);
                cell.setAttribute('col-index', colIndex);
                cell.setAttribute('id', getCellId(rowIndex, colIndex));
                
                cell.onclick = onCellClick;
                boardEl.appendChild(cell);
            }
        }
    }
    
    function onCellClick (e) {
        electrician.api.step(
            e.target.getAttribute('row-index'),
            e.target.getAttribute('col-index')
        ).done(function(resp) {
            updateCells(resp.cells);
            if (resp.isCompleted) {
                onGameCompleted(resp.stepCount, onUserAdded);
            }
        });
    }
    
    function updateCells(cells) {
        for (let rowIndex=1; rowIndex <= 5; rowIndex++) {
            if (! cells[rowIndex]) {
                continue;
            }
            for (let colIndex=1; colIndex <= 5; colIndex++) {
                if (cells[rowIndex][colIndex] === undefined) {
                    continue;
                }
                let cell = document.getElementById(getCellId(rowIndex, colIndex));
                let className = cells[rowIndex][colIndex] ? 'field bg-success' : 'field';
                cell.setAttribute('class', className);
            }
        }
    }
        
    function getCellId(rowIndex, colIndex) {
        return electrician.cellPrefix + '-' + rowIndex + '-' + colIndex;
    }
    
    function onUserAdded() {
        electrician.render();
    }
    
}