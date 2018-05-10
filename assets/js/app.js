function tick() {
    ctx.clearRect(0, 0, cw, ch),
        ctx.drawImage(bufferCanvas, 0, 0, current, txtH, sx, sy, current, txtH),
        ctx.save(),
        ctx.globalAlpha = 0.05,
        ctx.globalCompositeOperation = 'lighter',
        drawRays(current) ? (current++, current = Math.min(current, txtW), window.requestAnimationFrame(tick)) : fadeOut(),
        ctx.restore()
}

function fadeOut() {
    ctx.clearRect(0, 0, cw, ch),
        ctx.globalAlpha *= 0.95,
        ctx.drawImage(bufferCanvas, 0, 0, current, txtH, sx, sy, current, txtH),
        ctx.globalAlpha > 0.01 ? window.requestAnimationFrame(fadeOut) : window.setTimeout(restart, 500)
}

function restart() {
    for (let t = 0; t < rays.length; t++) rays[t].reset();
    ctx.globalAlpha = 1,
        buffer.clearRect(0, 0, txtW, txtH),
        current = 0,
        tick()
}

function drawRays(t) {
    let a = 0;
    ctx.beginPath();
    for (let r = 0; r < rays.length; r++) {
        let e = rays[r];
        e.col < t && (a += e.draw())
    }
    return ctx.stroke(), a !== rays.length;
}

function Ray(t, a, r) {
    this.col = a,
        this.row = t;
    let e = sx + a,
        i = sy + t,
        n = r,
        x = txtH / 1.5,
        o = pi2 * (this.row - 0.5 * x) / x;
    0 === o && (o = (Math.random() - 0.5) * pi2);
    let c = 0.02 * Math.sign(o),
        s = 2 * pi * (this.col - 0.5 * txtW) / txtW;
    0 === s && (s = (Math.random() - 0.5) * pi);
    let l = 0.02 * Math.sign(s);
    c += 0.005 * (Math.random() - 0.5);
    let h = 0,
        d = 2 * Math.random() + 2,
        f = !1;
    this.reset = function () {
        s = 2 * pi * (this.col - 0.5 * txtW) / txtW,
            o = pi2 * (this.row - 0.5 * x) / x,
        0 === o && (o = 0.5 * -pi2),
            h = 0,
            f = !1
    };
    this.draw = function () {
        return 0 > h ? (f || (buffer.fillStyle = n, buffer.fillRect(this.col, this.row, 1, 1), f = !0), 1) : (ctx.moveTo(e, i), ctx.quadraticCurveTo(e + Math.cos(s) * h * .5, i + Math.sin(s) * h * 0.5, e + Math.cos(o) * h, i + Math.sin(o) * h), o += c, s += l, h += Math.cos(o) * d, 0)
    }
}

let txt = '<Breith Barbot/>',
    txtH = 90,
    font = 'sans-serif',
    bg = '#242425',
    rayColor1 = '#093f7f', // Lettre
    rayColor2 = '#f12e59', // Effet 1/3 + 3/3
    rayColor3 = '#093f7f', // Effet 2/3
    canvas = document.getElementById('canvas'),
    ctx = canvas.getContext('2d'),
    cw = canvas.width = window.innerWidth,
    ch = canvas.height = window.innerHeight * 0.9,
    w2 = cw / 2,
    h2 = ch / 2,
    pi = Math.PI,
    pi2 = 0.5 * pi,
    txtCanvas = document.createElement('canvas'),
    txtCtx = txtCanvas.getContext('2d');
txtCtx.font = txtH + 'px ' + font,
    txtCtx.textBaseline = 'middle';
let txtW = Math.floor(txtCtx.measureText(txt).width);
txtCanvas.width = txtW,
    txtCanvas.height = 1.2 * txtH;
let gradient = ctx.createRadialGradient(w2, h2, 0, w2, h2, txtW);
gradient.addColorStop(0, rayColor3),
    gradient.addColorStop(0.5, rayColor2),
    gradient.addColorStop(1, rayColor1),
    ctx.strokeStyle = gradient,
    txtCtx.fillStyle = gradient,
    txtCtx.font = txtH + 'px ' + font,
    txtCtx.textBaseline = 'middle',
    txtCtx.fillText(txt, 0, 0.5 * txtH)
txtH *= 1.5;
let bufferCanvas = document.createElement('canvas');
bufferCanvas.width = txtW,
    bufferCanvas.height = txtH;
for (let buffer = bufferCanvas.getContext('2d'), sx = 0.5 * (cw - txtW), sy = 0.5 * (ch - txtH), rays = [], txtData = txtCtx.getImageData(0, 0, txtW, txtH), i = 0; i < txtData.data.length; i += 4) {
    let ii = i / 4,
        row = Math.floor(ii / txtW),
        col = ii % txtW,
        alpha = txtData.data[i + 3];
    if (0 !== alpha) {
        let c = 'rgba(';
        c += [txtData.data[i], txtData.data[i + 1], txtData.data[i + 2], alpha / 255],
            c += ')',
            rays.push(new Ray(Math.floor(ii / txtW), ii % txtW, c))
    }
}
let current = 0;

// https://developer.mozilla.org/en-US/docs/Web/API/Notifications_API/Using_the_Notifications_API
Notification.requestPermission();

function notifyMe(getip) {
    if (Notification.permission === 'granted') {
        // If it's okay let's create a notification
        let notification = new Notification('Hi ' + getip, {icon: 'assets/img/user.png'});
    }

    // Otherwise, we need to ask the user for permission
    else if (Notification.permission !== 'denied') {
        Notification.requestPermission(function (permission) {
            // If the user accepts, let's create a notification
            if (permission === 'granted') {
                let notification = new Notification('Hi ' + getip, {icon: 'assets/img/user.png'});
            }
        });
    }
}

tick();
notifyMe(getip());
