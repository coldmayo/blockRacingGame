<?php 
session_start();
    include("connection.php");
    include("functions.php");
    $user_data = check_login($con);
?>
<!DOCTYPE html>
<html>
<head>
<title>Benus</title>
<h1>Not Great Car Game That I Made in A Weekend</h1>
</head>
<body>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<style>
canvas {
    border:1px solid #d3d3d3;
    background-color: #f1f1f1;
}
</style>
<script>
var myGamePiece;
var myScore;
var scoreAdded = [];
var frameNo;
var daScore;
var hight_score = getHighScore(daScore,scoreAdded);
var myOponent;
var myBorder1;
var myBorder2;
var myBorder3;
var myBorder4;
var inBorder1;
var inBorder2;
var inBorder3;
var inBorder4;
var finishLine;
var healthOne;
var healthTwo;
var healthThree;
var enimNukes = [];

function uploadScore(username, score) {
    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("test").innerHTML = this.responseText;
        }
    }
    request.open("GET", "uploadscore.php?user=" + username + "&score=" + score);
    request.setRequestHeader("Content-type", "application/json");
    request.send();
}

function startGame() {
    frameNo = 0;
    myGamePiece = new component(15, 15, "purple", 25, 50);
    myScore = new component("9px", "Consolas", "black", 475, 25, "text");
    myHighScore = new component("9px", "Consolas", "black", 475, 55, "text");
    myOponent = new component(15, 15, "blue", 45, 50);
    myBorder1 = new component(10,250,"black",460,10);
    myBorder2 = new component(450,10,"black",10,10);
    myBorder3 = new component(10,250,"black",10,10);
    myBorder4 = new component(450,10,"black",10,250);
    inBorder1 = new component(10,125,"black",400,70);
    inBorder2 = new component(330,10,"black",70,70);
    inBorder3 = new component(10,125,"black",70,70);
    inBorder4 = new component(330,10,"black",70,185);
    finishLine = new component(50,10,"green",20,70);
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
    var lives = 2;
    for (i = 0; i < enimNukes.length; i += 1) {
        if (myGamePiece.crashWith(enimNukes[i])) {
            high_score = getHighScore(daScore,scoreAdded);
            myGameArea.stop();
            enimNukes.splice(0,enimNukes.length);
            return;
        } 
    }
    if (myGamePiece.crashWith(myBorder1) || myGamePiece.crashWith(myBorder2) || myGamePiece.crashWith(myBorder3) || myGamePiece.crashWith(myBorder4)) {
        high_score = getHighScore(daScore,scoreAdded);
        myGameArea.stop();
        nimNukes.splice(0,enimNukes.length);
    }
    else if (myGamePiece.crashWith(inBorder1) || myGamePiece.crashWith(inBorder2) || myGamePiece.crashWith(inBorder3) || myGamePiece.crashWith(inBorder4)) {
        high_score = getHighScore(daScore,scoreAdded);
        myGameArea.stop();
        enimNukes.splice(0,enimNukes.length);
        
    }
    else if (myGamePiece.crashWith(myOponent)) {
        high_score = getHighScore(daScore,scoreAdded);
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
        myBorder1.update();
        myBorder2.update();
        myBorder3.update();
        myBorder4.update();
        inBorder1.update();
        inBorder2.update();
        inBorder3.update();
        inBorder4.update();
        finishLine.update();
        daScore = Math.round(frameNo/10);
        myScore.text = "SCORE: " + daScore;
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

function getHighScore(daScore,scoreAdded) {
    scoreAdded.push(daScore);
    hs = Math.max.apply(Math,scoreAdded);
    if (localStorage.getItem('high_score') !== null) {
        if (hs > localStorage.getItem('high_score')) {
            localStorage.setItem('high_score',hs);
        }
    } else {
        localStorage.setItem('high_score',hs);
    }
    return localStorage.getItem('high_score');
}
</script>   
<button onClick="startGame();">Play</button>
<button onClick="window.location.reload();">Restart</button>
<a href="logout.php"><button>Logout</button></a>
</body>
</html>