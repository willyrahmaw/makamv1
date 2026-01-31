<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Peta Digital Makam Ngadisimo</title>

    <style>
        body {
            margin: 0;
            background: #111;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: Arial;
            color: white;
        }

        h2 {
            margin: 10px;
        }

        .map-wrapper {
            position: relative;
            width: 98vw;
            height: 90vh;
        }

        .map-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        svg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
        }

        /* ==========================
           WARNA PER BLOK / ZONA
           ========================== */
        polygon.A {
            fill: rgba(0, 150, 255, .25);
            stroke: #00cfff;
        }

        polygon.B {
            fill: rgba(0, 255, 120, .25);
            stroke: #00ff88;
        }

        polygon.C {
            fill: rgba(255, 255, 0, .25);
            stroke: #ffee00;
        }

        polygon.D {
            fill: rgba(255, 150, 0, .25);
            stroke: #ff8800;
        }

        polygon.E {
            fill: rgba(255, 0, 0, .25);
            stroke: #ff4444;
        }

        polygon:hover {
            fill: rgba(62, 194, 255, 0.95);
            stroke: orange;
        }

        polygon {
            cursor: pointer;
            stroke-width: 3;
            transition: .2s;
        }

        text {
            fill: white;
            font-weight: bold;
            font-size: 28px;
            pointer-events: none;
            text-shadow: 2px 2px 5px black;
        }

        @media(max-width:768px) {
            text {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>

    <h2>Peta Digital Makam Ngadisimo</h2>

    <div class="map-wrapper">

        <img src="peta13.png">

        <svg viewBox="0 0 1100 850">

            <!-- A -->
            <polygon class="A" points="60,760 220,760 240,650 80,680" onclick="klik('A1')" /><text x="120" y="740">A1</text>
            <polygon class="A" points="80,680 240,650 270,520 130,430" onclick="klik('A2')" /><text x="150" y="590">A2</text>
            <polygon class="A" points="130,430 270,520 330,410 210,380" onclick="klik('A3')" /><text x="220" y="470">A3</text>
            <polygon class="A" points="210,380 330,410 360,320 250,230" onclick="klik('A4')" /><text x="270" y="340">A4</text>
            <polygon class="A" points="250,230 360,320 440,110 330,160" onclick="klik('A5')" /><text x="310" y="240">A5</text>

            <!-- B -->
            <polygon class="B" points="220,760 430,720 440,650 240,650" onclick="klik('B1')" /><text x="320" y="730">B1</text>
            <polygon class="B" points="240,650 440,650 460,520 270,520" onclick="klik('B2')" /><text x="330" y="600">B2</text>
            <polygon class="B" points="270,520 460,520 480,400 330,410" onclick="klik('B3')" /><text x="370" y="480">B3</text>
            <polygon class="B" points="330,410 480,400 480,310 360,320" onclick="klik('B4')" /><text x="400" y="360">B4</text>
            <polygon class="B" points="360,320 480,310 500,20 440,110" onclick="klik('B5')" /><text x="410" y="180">B5</text>

            <!-- C -->
            <polygon class="C" points="430,720 630,720 630,640 440,650" onclick="klik('C1')" /><text x="520" y="700">C1</text>
            <polygon class="C" points="440,650 630,640 620,520 460,520" onclick="klik('C2')" /><text x="520" y="600">C2</text>
            <polygon class="C" points="460,520 620,520 620,380 480,400" onclick="klik('C3')" /><text x="520" y="470">C3</text>
            <polygon class="C" points="480,400 620,380 620,310 480,310" onclick="klik('C4')" /><text x="520" y="350">C4</text>
            <polygon class="C" points="480,310 620,310 600,160 500,20" onclick="klik('C5')" /><text x="520" y="240">C5</text>

            <!-- D -->
            <polygon class="D" points="630,720 820,690 810,600 630,640" onclick="klik('D1')" /><text x="710" y="690">D1</text>
            <polygon class="D" points="630,640 810,600 790,520 620,520" onclick="klik('D2')" /><text x="700" y="580">D2</text>
            <polygon class="D" points="620,520 790,520 780,380 620,380" onclick="klik('D3')" /><text x="690" y="460">D3</text>
            <polygon class="D" points="620,380 780,380 760,290 620,310" onclick="klik('D4')" /><text x="680" y="340">D4</text>
            <polygon class="D" points="620,310 760,290 750,230 600,160" onclick="klik('D5')" /><text x="680" y="240">D5</text>

            <!-- E -->
            <polygon class="E" points="820,690 1000,640 980,570 810,600" onclick="klik('E1')" /><text x="900" y="650">E1</text>
            <polygon class="E" points="810,600 980,570 970,500 790,520" onclick="klik('E2')" /><text x="880" y="560">E2</text>
            <polygon class="E" points="790,520 970,500 950,420 780,380" onclick="klik('E3')" /><text x="870" y="470">E3</text>
            <polygon class="E" points="780,380 950,420 930,340 760,290" onclick="klik('E4')" /><text x="850" y="360">E4</text>
            <polygon class="E" points="760,290 930,340 900,240 750,230" onclick="klik('E5')" /><text x="840" y="280">E5</text>

        </svg>
    </div>

    <p>Klik blok makam untuk detail.</p>

    <script>
        function klik(blok) {
            alert("Anda memilih blok " + blok);
        }
    </script>

</body>

</html>