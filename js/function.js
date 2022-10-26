const overlay = document.getElementById('tweet_form');
var modal_bool = false;

let year = document.getElementById('year');
let yearmonth_text = "";
let picker_month_box = document.getElementById('picker_month');
let picker_day_box = document.getElementById('picker_day');
let json_array;
let picker_overlay = document.getElementById('picker_overlay');
let text_form = document.getElementById('item');
const tweet_picker_show = document.getElementById('tweet_picker_show');

// schedule
let select_state = document.querySelector("select[name=category]");
var select_category = "";
var json_category = { 1: "豊田北高校", 2: "豊田東高校", 3: "豊田西高校" }

document.addEventListener("click", (e) => {
    let pattern = /^icon_plus/;
    let cancel = /^cancel/;
    let picker_month = /^m[0-9]{1,2}/;
    let picker_day = /^d[0-9]{8,8}/;
    let edit = /^edit[0-9]{1,4}/;

    if (pattern.test(e.target.id)) {
        modal_show(e);
    } else if (cancel.test(e.target.id)) {
        modal_close();
    } else if (e.target.id == 'picker_cancel') {
        picker_overlay_close();
    } else if (picker_month.test(e.target.id)) {
        let month = e.target.id.slice(1, 3);
        date_picker(year.textContent, month);
    } else if (picker_day.test(e.target.id)) {
        pickerdaycolor_change(e.target.id);
    } else if (e.target.id == 'year_next') {
        year.textContent = parseInt(year.textContent) + 1;
    } else if (e.target.id == 'year_previous') {
        year.textContent = parseInt(year.textContent) - 1;
    } else if (e.target.id == 'picker_input') {
        picker_overlay_close();
        text_form.value = "";
        text_form.value = JSON.stringify(json_array);
    } else if (e.target.id == 'tweet_picker_show') {
        if (select_state.value == '1' || select_state.value == '2' || select_state.value == '3') {
            picker_overlay_open();
        }
    } else if (edit.test(e.target.id)) {
        modal_show_edit(e);
    } else if (e.target.id == "btn") {
        resetPreview();
    }
});

function pickerdaycolor_change(targetid) {
    let day_elem = document.getElementById(targetid);
    var day = targetid.slice(7, 9);
    if (yearmonth_text == targetid.slice(1, 7) && !day_elem.hasAttribute('alt')) {
        Object.assign(json_array["workingday"], { [day]: 1 });

        day_elem.style.backgroundColor = "rgb(3, 169, 244)";
        day_elem.setAttribute('alt', '1');
    } else {
        delete json_array["workingday"][day];

        day_elem.style.backgroundColor = "#fdecff";
        day_elem.removeAttribute('alt');
    }
}

function modal_show(e) {
    overlay.style.display = 'block';
    modal_bool = true;
}

function modal_show_edit(e) {
    var btelem = document.createElement('button');
    btelem.className = 'delete';
    btelem.id = 'delete';
    btelem.value = 'delete';
    btelem.name = 'delete';
    btelem.innerText = '削除';
    document.getElementById('form').appendChild(btelem);

    itemnum = e.target.id.slice(4, 7);
    document.getElementById('num').value = itemnum;
    document.getElementById('submit').textContent = "変更する";
    categoryelem = document.getElementById('category');
    //categoryelem.disabled = true;
    var category = { 'tweet': 0, 'calenderkita': 1, 'calenderhigashi': 2, 'calendernishi': 3 };
    categorynum = document.getElementById('cat' + itemnum).innerHTML;
    categoryelem.selectedIndex = category[categorynum];

    document.getElementById('item').innerHTML = document.getElementById('text' + itemnum).innerHTML;
    document.getElementById('privatepublic').selectedIndex = document.getElementById('prv' + itemnum).innerHTML;

    var imagenum = 'img' + itemnum;
    var imageelem = document.getElementById(imagenum);

    if (imageelem) {
        var imageurl = imageelem.getAttribute('src');
        var elem = document.getElementById('preview');
        elem.style.display = "block";
        var img = new Image();
        img.src = imageurl;
        elem.appendChild(img);

        var button = document.createElement('button');
        button.id = "btn";
        button.textContent = "×";
        elem.appendChild(button);
    }

    if (select_state.value == '1' || select_state.value == '2' || select_state.value == '3') {
        tweet_picker_show.style.display = "block";
    }

    overlay.style.display = 'block';
    modal_bool = true;
}

function modal_close() {
    modal_bool = false;
    overlay.style.display = 'none';
}

select_state.addEventListener('change', function () {
    select_category = json_category[select_state.value];

    if (select_state.value == '1' || select_state.value == '2' || select_state.value == '3') {
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
        yearmonthday = String(year) + String('000' + inmonth).slice(-2) + String('000' + inday).slice(-2);
        html += "<a id='d" + yearmonthday + "'>" + inday + "</a>";
        if (inbegin == inend) break;
        date.setDate(inday + 1);
    }
    html += "<a id='picker_input' class='picker_input'>カレンダー作成</a>";

    json_array = make_jsonarray(month);
    yearmonth_text = String(year) + String('000' + month).slice(-2);
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
    return JSON.parse(json);
}

text_form.addEventListener('focus', () => {

});

text_form.addEventListener('blur', () => {

});

function resetPreview() {
    var element = document.getElementById("preview");
    while (element.firstChild) {
        element.removeChild(element.firstChild);
    }
}

document.getElementById('image').addEventListener('change', function (e) {
    resetPreview();
    var elem = document.getElementById('preview');
    elem.style.display = "block";
    for (var num in e.target.files) {
        var file = e.target.files[num];
        var blobUrl = window.URL.createObjectURL(file);
        var img = new Image();
        img.src = blobUrl;
        elem.appendChild(img);

        var button = document.createElement('button');
        button.id = "btn";
        button.textContent = "×";
        elem.appendChild(button);
    }
});
