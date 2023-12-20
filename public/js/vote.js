document.addEventListener('DOMContentLoaded', () => {

    document.getElementById('vote-btn').addEventListener('click', function (evt) {
console.log('VOTE');
        document.getElementById('votingForm').submit();
    }); 

    document.getElementById('back-btn').addEventListener('click', function (evt) {
console.log('BACK');
    }); 
});
