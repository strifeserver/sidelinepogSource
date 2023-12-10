Echo.channel('all-bets-'+eventIdString)
.listen('AllBets', function(data) {
    fillBetsTable(data.allbets);
});


Echo.channel('place-bet-'+eventIdString)
.listen('PlaceBet', function(data) {
    console.log(data);
    updateBets(data.betData.betOne,data.betData.betTwo,data.betData.betFive,data.betData.betTen,data.betData.betTwenty,data.betData.betForty);
});
