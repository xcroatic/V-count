function dateCheck(){
    const startDate = document.getElementById('start');
    const endDate = document.getElementById('end').value.split('-');

    startDate.setAttribute("max", endDate[0] + "-" + endDate[1] + "-" + (endDate[2]-1));
}
