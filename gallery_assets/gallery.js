var bigboi = document.getElementById("bigboi");
var megaboi = document.getElementById("megaboi");
var left = document.getElementById("left");
var right = document.getElementById("right");
var close = document.getElementById("close");
var newtab = document.getElementById("newtab");
bigboi.style.width = "0%";
megaboi.style.height = "0%";
left.style.width = "0%";
right.style.width = "0%";
close.style.width = "0%";
newtab.style.width = "0%";
function mediashow(file){
    bigboi.src = path+file;
    megaboi.src = "gallery_assets/tl80.png";
    left.src = "gallery_assets/previous.png";
    right.src = "gallery_assets/next.png";
    close.src = "gallery_assets/close.png";
    newtab.src = "gallery_assets/newtab.png";
    bigboi.style.width = "100%";
    megaboi.style.height = "100%";
    left.style.width = "100%";
    right.style.width = "100%";
    close.style.width = "100%";
    newtab.style.width = "100%";
}

document.onkeydown = function(evt) {
    evt = evt || window.event;
    if (evt.keyCode == 27) {
        bigboi.src="gallery_assets/void.png";
        megaboi.src="gallery_assets/void.png";
        left.src="gallery_assets/void.png";
        right.src="gallery_assets/void.png";
        close.src="gallery_assets/void.png";
        newtab.src="gallery_assets/void.png";
        bigboi.style.width = "0%";
        megaboi.style.height = "0%";
        left.style.width = "0%";
        right.style.width = "0%";
        close.style.width = "0%";
        newtab.style.width = "0%";
    }
};
function prev(){
    fn = bigboi.src.split('/');
    ffn = fn[fn.length-2]+'/'+fn[fn.length-1];
    var pos = imgsns.indexOf(ffn);
    console.log(ffn+pos);
    if (pos > 0){
        bigboi.src = path+imgs[pos-1];
    }
}
function next(){
    fn = bigboi.src.split('/');
    ffn = fn[fn.length-2]+'/'+fn[fn.length-1];
    var pos = imgsns.indexOf(ffn);
    console.log(ffn+pos);
    if (pos < imgs.length-1){
        bigboi.src = path+imgs[pos+1];
    }
}
function new_tab(){
    window.open(bigboi.src,'_blank');
}