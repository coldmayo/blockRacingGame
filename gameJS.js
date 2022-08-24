var myGamePiece;
var myHighScore;
var myScore;
var scoreAdded = [];
var frameNo;
var daScore;
var myOponent;
var Borders = [];
var finishLine;
var enimNukes = [];

function startGame() {
    frameNo = 0;
    myGamePiece = new component(15, 15, "purple", 25, 50);
    myScore = new component("9px", "Verdana", "black", 475, 25, "text");
    myHighScore = new component("9px", "Verdana", "black", 475, 55, "text");
    myOponent = new component(15, 15, "blue", 45, 50);
    Borders.push(new component(8,250,"black",460,10));
    Borders.push(new component(8,250,"black",460,10));
    Borders.push(new component(450,8,"black",10,10));
    Borders.push(new component(8,250,"black",10,10));
    Borders.push(new component(458,8,"black",10,260));
    Borders.push(new component(8,125,"black",400,70));
    Borders.push(new component(330,8,"black",70,70));
    Borders.push(new component(8,125,"black",70,70));
    Borders.push(new component(338,8,"black",70,195));
    finishLine = new component(52,8,"grey",18,70);
    myGameArea.start();
}

var myGameArea = {
    canvas : document.createElement("canvas"),
    start : function() {
        this.canvas.width = 550;
        this.canvas.height = 270;
        this.context = this.canvas.getContext("2d");
        document.body.insertBefore(this.canvas, document.body.childNodes[0]);
        this.frameNo = 0;
        this.frames = 10;
        this.interval = setInterval(updateGameArea, this.frames);
        window.addEventListener('keydown', function (e) {
            myGameArea.keys = (myGameArea.keys || []);
            myGameArea.keys[e.keyCode] = (e.type == "keydown");
        })
        window.addEventListener('keyup', function (e) {
            myGameArea.keys[e.keyCode] = (e.type == "keydown");            
        })
    }, 
    clear : function(){
        this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
    },
    stop : function() {
        clearInterval(this.interval);
    }
}

function component(width, height, color, x, y,type) {
    this.type = type
    this.gamearea = myGameArea;
    this.width = width;
    this.height = height;
    this.angle = 0;
    this.moveAngle = 1;
    this.speed = 1;
    this.speedX = 0;
    this.speedY = 0;    
    this.x = x;
    this.y = y;    
    this.update = function() {
        ctx = myGameArea.context;
        if (this.type == "text") {
            ctx.font = this.width + " " + this.height;
            ctx.fillStyle = color;
            ctx.fillText(this.text, this.x, this.y);
        }
        else {
            ctx.fillStyle = color;
            ctx.fillRect(this.x, this.y, this.width, this.height);
        }
    }
    this.newPos = function() {
        this.x += this.speedX;
        this.y += this.speedY;        
    }
    this.newEnimPos = function() {
        if (this.y < 200 && this.x == 45) {
            this.x += this.speed * Math.sin(this.angle);
            this.y += this.speed * Math.cos(this.angle);
        }
        else if (this.y == 200 && this.x < 420 ) {
            this.x += this.speed * Math.cos(this.angle);
            this.y += this.speed * Math.sin(this.angle);
        }
        else if (this.y > 45 && this.x == 420) {
            this.x += this.speed * Math.sin(this.angle);
            this.y -= this.speed * Math.cos(this.angle);
        }
        else {
            this.x -= this.speed * Math.cos(this.angle);
            this.y += this.speed * Math.sin(this.angle);
        }
    }
    this.crashWith = function(otherobj) {
        var myleft = this.x;
        var myright = this.x + (this.width);
        var mytop = this.y;
        var mybottom = this.y + (this.height);
        var otherleft = otherobj.x;
        var otherright = otherobj.x + (otherobj.width);
        var othertop = otherobj.y;
        var otherbottom = otherobj.y + (otherobj.height);
        var cras = true;
        if ((mybottom < othertop) ||
        (mytop > otherbottom) ||
        (myright < otherleft) ||
        (myleft > otherright)) {
            cras = false;
        }
        return cras;
    }

}

function updateGameArea() {
    var x,y;
    for (i = 0; i < enimNukes.length; i += 1) {
        if (myGamePiece.crashWith(enimNukes[i])) {
            myGameArea.stop();
            enimNukes.splice(0,enimNukes.length);
            return;
        } 
    }
    for (i = 0; i < Borders.length; i += 1) {
        if (myGamePiece.crashWith(Borders[i])) {
            myGameArea.stop();
            return;
        } 
    }
    if (myGamePiece.crashWith(myOponent)) {
        myGameArea.stop();
        enimNukes.splice(0,enimNukes.length);
    }
    else {
        myGameArea.clear();
        frameNo += 1;
        if (daScore >= 50) {
            if (frameNo == 1 || everyinterval(150)) {
                x = Math.floor(Math.random() * 420);
                y = Math.floor(Math.random() * 210);
                enimNukes.push(new component(15, 15, "red", x, y));
            }
        }
        for (i = 0; i < enimNukes.length; i += 1) {
            enimNukes[i].update();
        }
        if (enimNukes.length == 32) {
            enimNukes.splice(0,1);
        }
        for (i = 0; i < Borders.length; i += 1) {
           Borders[i].update();
        }
        finishLine.update();
        daScore = Math.round(frameNo/10);
        myScore.text = "Score: " + daScore;
        myHighScore.text = "High Score: " + getHighScore(daScore,scoreAdded);

        myScore.update();
        myHighScore.update();
        myOponent.newEnimPos();
        myOponent.update();
        myGamePiece.speedX = 0;
        myGamePiece.speedY = 0;
        if (myGamePiece.width < 15) {
            myGamePiece.width += 4;
            myGamePiece.height += 4;
        }    
        if (myGameArea.keys && myGameArea.keys[37]) {myGamePiece.speedX = -1; }
        if (myGameArea.keys && myGameArea.keys[39]) {myGamePiece.speedX = 1; }
        if (myGameArea.keys && myGameArea.keys[38]) {myGamePiece.speedY = -1; }
        if (myGameArea.keys && myGameArea.keys[40]) {myGamePiece.speedY = 1; }
        if (myGameArea.keys && myGameArea.keys[16]) {
            myGamePiece.speedX = myGamePiece.speedX*2;
            myGamePiece.speedY = myGamePiece.speedY*2;
            myGamePiece.width -= 4;
            myGamePiece.height -= 4;
        }
        myGamePiece.newPos();    
        myGamePiece.update();
    }
}
function everyinterval(n) {
    if ((frameNo / n) % 1 == 0) {return true;}
    return false;
}

  
function setCookie(name, value, days) {
    var expires = "";
    if (days) {
      var date = new Date();
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
      expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') c = c.substring(1, c.length);
      if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
  }
function getHighScore(daScore,scoreAdded) {
    scoreAdded.push(daScore);
    hs = Math.max.apply(Math,scoreAdded);
    if (getCookie('highscore') !== null) {
        if (hs > getCookie('highscore')) {
            setCookie('highscore',hs,3);
        }
    } else {
        setCookie('highscore',hs,3);
    }
    return getCookie('highscore');
}
