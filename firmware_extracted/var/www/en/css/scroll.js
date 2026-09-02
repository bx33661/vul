// code to do the floater
window.onerror = null;
var topMargin = 350; // 여기가 높이 조절 하는곳..............................................................................
var slideTime = 800;// 여기가 속도 조절 하는곳..............................................................................
var ns6 = (!document.all && document.getElementById);
var ie4 = (document.all);
var ns4 = (document.layers);aksw
function layerObject(id,left) {
    if (ns6) {
        this.obj = document.getElementById(id).style;
        this.obj.left = left;
        return this.obj;
    }
    else if(ie4) {
        this.obj = document.all[id].style;
        this.obj.left = left;
        return this.obj;
    }
    else if(ns4) {
        this.obj = document.layers[id];
        this.obj.left = left;
        return this.obj;
    }
}

function layerSetup() {
    floatLyr = new layerObject('floatLayer', 900); //여기가 좌우 조절하는곳.........................................................
    window.setInterval("main()", 10)
}

function floatObject() {
    if (ns4 || ns6) {
        findHt = window.innerHeight;
    } else if(ie4) {
        findHt = document.body.clientHeight;
   }
} 

function main() {
    if (ns4) {
        this.currentY = document.layers["floatLayer"].top;
        this.scrollTop = window.pageYOffset;
        mainTrigger();
    }
    else if(ns6) {
        this.currentY = parseInt(document.getElementById('floatLayer').style.top);
        this.scrollTop = scrollY;
        mainTrigger();
    } else if(ie4) {
        this.currentY = floatLayer.style.pixelTop;
        this.scrollTop = document.body.scrollTop;
        mainTrigger();
    }
}

function mainTrigger() {
    var newTargetY = this.scrollTop + this.topMargin;
    if ( this.currentY != newTargetY ) {
        if ( newTargetY != this.targetY ) {
            this.targetY = newTargetY;
            floatStart();
        }
        animator();
    }
}

function floatStart() {
    var now = new Date();
    this.A = this.targetY - this.currentY;
    this.B = Math.PI / ( 2 * this.slideTime );
    this.C = now.getTime();
    if (Math.abs(this.A) > this.findHt) {
        this.D = this.A > 0 ? this.targetY - this.findHt : this.targetY + this.findHt;
        this.A = this.A > 0 ? this.findHt : -this.findHt;
    }
    else {
        this.D = this.currentY;
    }
}

function animator() {
    var now = new Date();
    var newY = this.A * Math.sin( this.B * ( now.getTime() - this.C ) ) + this.D;
    newY = Math.round(newY);
    if (( this.A > 0 && newY > this.currentY ) || ( this.A < 0 && newY < this.currentY )) {
        if ( ie4 )document.all.floatLayer.style.pixelTop = newY;
        if ( ns4 )document.layers["floatLayer"].top = newY;
        if ( ns6 )document.getElementById('floatLayer').style.top = newY + "px";
    }
}

function start() {

        if(ns6||ns4) {
            pageWidth = innerWidth;
            pageHeight = innerHeight;
            layerSetup();
            floatObject();
        }
        else if(ie4) {
            pageWidth = document.body.clientWidth;
            pageHeight = document.body.clientHeight;
            layerSetup();
            floatObject();
        }


}