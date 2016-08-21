
var i = 1;
function reset()
 {
	var i = 1;
}

function add_new()
 {

    //Create input for red value
    var color_form = document.getElementById('column1');
     //Create input for color value
    var create = document.createElement('input');
    create.setAttribute('name', 'color[' + i + ']');
    create.setAttribute('class', 'song_entry_color');
    create.setAttribute('placeholder', 'color');
    color_form.appendChild(create);
    
    var linebreak = document.createElement('br');
    color_form.appendChild(linebreak);
    
    
    var create = document.createElement('input');
    create.setAttribute('name', 'red[' + i + ']');
    create.setAttribute('class', 'song_entry');
    create.setAttribute('placeholder', 'red');
    color_form.appendChild(create);

    //Create input for green value
    var create = document.createElement('input');
    create.setAttribute('name', 'green[' + i + ']');
    create.setAttribute('class', 'song_entry');
    create.setAttribute('placeholder', 'green');
    color_form.appendChild(create);

    //Create input for blue value
    var create = document.createElement('input');
    create.setAttribute('name', 'blue[' + i + ']');
    create.setAttribute('class', 'song_entry');
    create.setAttribute('placeholder', 'blue');
    color_form.appendChild(create);

    var linebreak = document.createElement('br');
    color_form.appendChild(linebreak);
    var linebreak = document.createElement('br');
    color_form.appendChild(linebreak);
    i++;
}

function additional_img(){
    var uploads = document.getElementById('img_uploads');
    var create = document.createElement('input');
    create.setAttribute('name', 'img[' + i + ']');
    create.setAttribute('type', 'file');
    create.setAttribute('id', 'img');
    uploads.appendChild(create);
    i++;
    
}

function new_segment(){
	var weekly = document.getElementById('weekly');
	var create = document.createElement('input');
	create.setAttribute('name', 'segment[' + i + ']');
	create.setAttribute('placeholder', 'Segment Name');
	weekly.appendChild(create);
	var linebreak = document.createElement('br');
    weekly.appendChild(linebreak);
	var create = document.createElement('textarea');
	create.setAttribute("name", 'note[' + i + ']');
	create.setAttribute("row", 4);
	create.setAttribute("col", 50);
	weekly.appendChild(create);
	var linebreak = document.createElement('br');
    weekly.appendChild(linebreak);
	var linebreak = document.createElement('br');
    weekly.appendChild(linebreak);
	i++;
}