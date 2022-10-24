const overlay = document.getElementById('tweet_form');
var modal_bool = false;

let year = document.getElementById('year');
let yearmonth_text = "";
let picker_month_box = document.getElementById('picker_month');
let picker_day_box = document.getElementById('picker_day');
let json_array = "";
let picker_overlay = document.getElementById('picker_overlay');
let text_form = document.getElementById('item');
const tweet_picker_show = document.getElementById('tweet_picker_show');

document.addEventListener("click", (e) => {
    let pattern = /^icon_plus/;
    let cancel = /^cancel/;
    let picker_month = /^m[0-9]{2,2}/;

    if (pattern.test(e.target.id)) {
        modal_show(e);
    } else if (cancel.test(e.target.id)) {
        modal_close();
    } else if (e.target.id == 'picker_cancel') {
        picker_overlay_close();
    } else if (picker_month.test(e.target.id)) {
        let month = e.target.id.slice(1, 3);
        date_picker(year.textContent, month);

    } else if (e.target.id == 'year_next') {
        year.textContent = parseInt(year.textContent) + 1;
    } else if (e.target.id == 'year_previous') {
        year.textContent = parseInt(year.textContent) - 1;
    } else if (e.target.id == 'picker_input') {
        picker_overlay_close();
        text_form.value = "";
        text_form.value = json_array;
    } else if (e.target.id == 'tweet_picker_show') {
        if (select_state.value == 'calenderhigashi' || select_state.value == 'calenderkita' || select_state.value == 'calendernishi') {
            picker_overlay_open();
        }
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
var select_category = "";
var json_category = { calenderhigashi: "豊田東高校", calenderkita: "豊田北高校", calendernishi: "豊田西高校" }
select_state.addEventListener('change', function () {
    select_category = json_category[select_state.value];

    if (select_state.value == 'calenderhigashi' || select_state.value == 'calenderkita' || select_state.value == 'calendernishi') {
        tweet_picker_show.style.display = "block";
    } else {
        tweet_picker_show.style.display = "none";
    }
});

function picker_overlay_open() {
    picker_overlay.style.display = 'block';
}

function picker_overlay_close() {
    picker_overlay.style.display = 'none';
    picker_month_box.style.display = "block";
    picker_day_box.style.display = "none";
}

function month_picker() {
    var picker = document.createElement('div');
    picker.id = 'picker_box';
    picker.className = 'picker_box';
    picker.innerHTML = `
        <div class='picker_year'>
            <a id="year_previous"><　　　</a>
            <a id="year">2022</a>
            <a id="year_next">　　　></a>
            <a id="picker_cancel">×</a>
        </div>
        <div class='picker_month'>
            <a id="m01">1月</a>
            <a id="m02">2月</a>
            <a id="m03">3月</a>
            <a id="m04">4月</a>
            <a id="m05">5月</a>
            <a id="m06">6月</a>
            <a id="m07">7月</a>
            <a id="m08">8月</a>
            <a id="m09">9月</a>
            <a id="m10">10月</a>
            <a id="m11">11月</a>
            <a id="m12">12月</a>
        </div>`;
    return picker;
}

function date_picker(year, month) {
    let end = new Date(year, month, 0);
    let begin = new Date(year, month - 1, 1);
    let begin_monday = get_beginMonday(begin);
    let end_sunday = get_endSunday(end);

    var date = new Date(begin_monday);
    var enddate = new Date(end_sunday);
    var inendmonth = enddate.getMonth() + 1;
    var inendday = enddate.getDate();
    var inend = String(inendmonth) + String(inendday);

    var html = "<div class='picker_day'><div class='picker_week'><a>月</a><a>火</a><a>水</a><a>木</a><a>金</a><a>土</a><a>日</a></div>";
    for (index = 0; index < 50; index++) {
        inmonth = date.getMonth() + 1;
        inday = date.getDate();
        inbegin = String(inmonth) + String(inday);
        html += "<a id='d" + inday + "'>" + inday + "</a>";
        if (inbegin == inend) break;
        date.setDate(inday + 1);
    }
    html += "<a id='picker_input' class='picker_input'>カレンダー作成</a>";

    json_array = make_jsonarray(month);

    picker_month_box.style.display = "none";

    picker_day_box.innerHTML = html;
    picker_day_box.style.display = "block";
}

function get_beginMonday(target_date) {
    let this_year = target_date.getFullYear();
    let this_month = target_date.getMonth();
    let date = target_date.getDate();
    let day_num = target_date.getDay();
    let sunday = date - day_num;
    let this_monday = sunday + 1;
    if (sunday == 1) this_monday = sunday - 6;

    //月曜日の年月日
    let start_date = new Date(this_year, this_month, this_monday);
    start_date = start_date.getFullYear() + "/" + (start_date.getMonth() + 1) + "/" + start_date.getDate();

    return start_date;
}

function get_endSunday(target_date) {
    let this_year = target_date.getFullYear();
    let this_month = target_date.getMonth();
    let date = target_date.getDate();
    let day_num = target_date.getDay();
    let sunday = date - day_num;
    let this_monday = sunday + 1;
    let this_sunday = this_monday + 6;
    if (sunday == target_date.getDate()) this_sunday = sunday;

    //日曜日の年月日
    let end_date = new Date(this_year, this_month, this_sunday);
    end_date = end_date.getFullYear() + "/" + (end_date.getMonth() + 1) + "/" + end_date.getDate();

    return end_date;
}

function make_jsonarray(month) {
    json = `{"year":"` + year.textContent + `","month":"` + month + `","workingday":{},"target":"` + select_category + `"}`;
    console.log(json);
    return json;
}

text_form.addEventListener('focus', () => {

});

text_form.addEventListener('blur', () => {

});