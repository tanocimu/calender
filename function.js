const overlay = document.getElementById('tweet_form');
var modal_bool = false;
document.addEventListener("click", (e) => {
    let pattern = /^icon_plus/;
    let cancel = /^cancel/;
    if (pattern.test(e.target.id)) {
        modal_show(e);
    } else if (cancel.test(e.target.id)) {
        modal_close();
    }
});

function modal_show(e) {
    overlay.style.display = 'block';
    modal_bool = true;
}

function modal_close() {
    modal_bool = false;
    overlay.style.display = 'none';
}

// schedule
let select_state = document.querySelector("select[name=category]");
select_state.addEventListener('change', function () {

    if (select_state.value == "calenderhigashi") {
        console.log(select_state.value);

        var elem = document.getElementById('tweet_form');


    }
});