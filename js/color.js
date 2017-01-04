var i = 1;
var color_form = document.getElementById("column1");
var linebreak = document.createElement("BR");

function reset() {
    i = 1;
}

function createColor(color) {
    var create = document.createElement("INPUT");
    create.setAttribute("name", color + "[" + i + "]");
    create.setAttribute("class", "song_entry");
    create.setAttribute("placeholder", color);
    color_form.appendChild(create);
}

function add_new() {
    color_form.appendChild(linebreak.cloneNode(true));
    createColor("red");
    createColor("green");
    createColor("blue");
    color_form.appendChild(linebreak.cloneNode(true));
    color_form.appendChild(linebreak.cloneNode(true));
    i += 1;
}


function additional_img() {
    var uploads = document.getElementById("img_uploads");
    var create = document.createElement("INPUT");
    create.setAttribute("name", "img[" + i + "]");
    create.setAttribute("type", "file");
    create.setAttribute("id", "img");
    uploads.appendChild(create);
    i += 1;
}

function new_segment() {
    var weekly = document.getElementById("weekly");
    var create = document.createElement("INPUT");
    create.setAttribute("name", "segment[" + i + "]");
    create.setAttribute("placeholder", "Segment Name");
    weekly.appendChild(create);
    weekly.appendChild(linebreak.cloneNode(true));
    create = document.createElement("textarea");
    create.setAttribute("name", "note[" + i + "]");
    create.setAttribute("row", 4);
    create.setAttribute("col", 50);
    weekly.appendChild(create);
    weekly.appendChild(linebreak.cloneNode(true));
    weekly.appendChild(linebreak.cloneNode(true));
    i += 1;
}
