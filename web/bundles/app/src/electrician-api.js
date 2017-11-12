function ElectricianApi() {
    
    this.step = function (rowIndex, colIndex) {
        let data = {
            rowIndex: rowIndex,
            colIndex: colIndex
        };
        return $.post('/electrician', data);
    }
    
}