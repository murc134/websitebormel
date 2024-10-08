<!DOCTYPE html>

<html lang="en">

    <head>

        <meta charset="utf-8" />
        <?php
        include_once dirname(__FILE__) . '/../config/defines.php';
        include_once dirname(__FILE__) . '/../config/encryption.php';
        include_once dirname(__FILE__) . '/../tools/mobiledetect/Mobile_Detect.php';
        include_once dirname(__FILE__) . '/../tools/translation.php';

        include_once dirname(__FILE__) . '/../tools/JSGenerator.php';
        include_once dirname(__FILE__) . '/../tools/functions.php';
        ?>
        <title>HTML5 Snake</title>

        <style type="text/css">
            #game {
                max-width:500px; 
                width: 100%;
                text-align: justify;
            }
            #game svg{
                height: auto;
                position: relative;
                width:80%; 
                max-width: 450px;
                min-width: 200px;
                left: 10%;

            }
            canvas {
                border: solid black;
                position: relative;
                height: auto;
                position: relative;
                width:80%; 
                left: 10%;
            }
        </style>



        <script type="text/javascript">
            /**
             * @license HTML5 experiment Snake
             * http://www.xarg.org/project/html5-snake/
             *
             * Copyright (c) 2011, Robert Eisele (robert@xarg.org)
             * Dual licensed under the MIT or GPL Version 2 licenses.
             **/
            function init() {

                var intval = <?php $md = new Mobile_Detect(); echo $md->isMobile() ? 120 : 30; ?>;
                var ctx;
                var turn = [];
                var xV = [-1, 0, 1, 0];
                var yV = [0, -1, 0, 1];
                var queue = [];
                var elements = 1;
                var map = [];
                var X = 5 + (Math.random() * (45 - 10)) | 0;
                var Y = 5 + (Math.random() * (30 - 10)) | 0;
                var direction = Math.random() * 3 | 0;
                var interval = 0;
                var score = 0;
                var inc_score = 50;
                var sum = 0, easy = 0;
                var i, dir;
                var canvas = document.getElementById('canvas');
                for (i = 0; i < 45; i++) {
                    map[i] = [];
                }
                canvas.setAttribute('width', 45 * 10);
                canvas.setAttribute('height', 30 * 10);
                ctx = canvas.getContext('2d');

                function handleKeyEvent(keyCode) {
                    var kc = keyCode;
                    if (keyCode === 68) // right
                    {
                        kc = 39;
                    }
                    else if (keyCode === 65) // left
                    {
                        kc = 37;
                    }
                    else if (keyCode === 87) // top
                    {
                        kc = 38;
                    }
                    else if (keyCode === 83) // bottom
                    {
                        kc = 40;
                    }

                    var code = kc - 37;

                    /*
                     * 0: left
                     * 1: up
                     * 2: right
                     * 3: down
                     **/

                    if (0 <= code && code < 4 && code !== turn[0]) {
                        turn.unshift(code);
                    } else if (-5 == code) {
                        if (interval) {
                            window.clearInterval(interval);
                            interval = null;
                        } else {
                            interval = window.setInterval(clock, 60);
                        }
                    } else { // O.o
                        dir = sum + code;
                        if (dir == 44 || dir == 94 || dir == 126 || dir == 171) {
                            sum += code
                        } else if (dir === 218)
                            easy = 1;
                    }
                }
                //document.body.appendChild(canvas);
                function placeFood() {
                    var x, y;
                    do {
                        x = Math.random() * 45 | 0;
                        y = Math.random() * 30 | 0;
                    } while (map[x][y]);
                    map[x][y] = 1;
                    ctx.strokeRect(x * 10 + 1, y * 10 + 1, 10 - 2, 10 - 2);
                }
                placeFood();
                function clock() {
                    ctx.font = '48px serif';
                    //ctx.fillText('Hello world', 10, 50);
                    if (easy) {
                        X = (X + 45) % 45;
                        Y = (Y + 30) % 30;
                    }
                    --inc_score;
                    if (turn.length) {
                        dir = turn.pop();
                        if ((dir % 2) !== (direction % 2)) {
                            direction = dir;
                        }
                    }
                    if (
                            (easy || (0 <= X && 0 <= Y && X < 45 && Y < 30))
                            && 2 !== map[X][Y]) {
                        if (1 === map[X][Y]) {
                            score += Math.max(5, inc_score);
                            inc_score = 50;
                            placeFood();
                            elements++;
                        }
                        ctx.fillRect(X * 10, Y * 10, 10 - 1, 10 - 1);
                        map[X][Y] = 2;
                        queue.unshift([X, Y]);
                        X += xV[direction];
                        Y += yV[direction];
                        if (elements < queue.length) {
                            dir = queue.pop()
                            map[dir[0]][dir[1]] = 0;
                            ctx.clearRect(dir[0] * 10, dir[1] * 10, 10, 10);
                        }
                    } else if (!turn.length) {
                        if (console.log("You lost! Play again? Your Score is " + score)) {
                            ctx.clearRect(0, 0, 450, 300);
                            queue = [];
                            elements = 1;
                            map = [];
                            X = 5 + (Math.random() * (45 - 10)) | 0;
                            Y = 5 + (Math.random() * (30 - 10)) | 0;
                            direction = Math.random() * 3 | 0;
                            score = 0;
                            inc_score = 50;
                            for (i = 0; i < 45; i++) {
                                map[i] = [];
                            }
                            placeFood();
                        } else {
                            window.clearInterval(interval);
                            //window.location = "";
                            init();
                        }
                    }
                }
                interval = window.setInterval(clock, intval);
                document.onkeydown = function (e) {
                    handleKeyEvent(e.keyCode);
                    reactOnKeyPress(e.keyCode, _currentFill);
                };
                document.onkeyup = function (e) {
                    handleKeyEvent(e.keyCode);
                    reactOnKeyPress(e.keyCode, _whiteFill);
                };
                left.mousedown(function (e) {
                    //reactOnKeyPress(37, _currentFill);
                    handleKeyEvent(37);
                });
                right.mousedown(function (e) {
                    //reactOnKeyPress(39, _currentFill);
                    handleKeyEvent(39);
                });
                up.mousedown(function (e) {
                    //reactOnKeyPress(38, _currentFill);
                    handleKeyEvent(38);
                });
                down.mousedown(function (e) {
                    //reactOnKeyPress(40, _currentFill);
                    handleKeyEvent(40);
                });
                
            }
        </script>
    </head>

    <body onload="">
        <div id="game">
            <h2 class="text-center"><?php T("please_wait"); ?>...</h2>
            <?php T("cv_exporting"); ?><br><br>
            <canvas id="canvas" ></canvas>
            <br>

            <svg version="1.1" id="SVG_Controls" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 width="300px" height="200px" viewBox="0 0 300 200" enable-background="new 0 0 300 200" xml:space="preserve">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M20.165,198.143c-2.849-0.536-5.686-1.038-8.106-2.866
                  c-3.719-2.799-6.188-6.346-6.217-11.083c-0.094-20.517-0.088-41.042-0.11-61.558c-0.006-3.747-0.006-7.481,0.123-11.229
                  c0.206-6.172,5.44-12.069,11.668-13.045c1.661-0.257,3.368-0.413,5.057-0.413c23.169-0.022,46.345-0.006,69.521-0.012
                  c5.261,0,8.238-3.011,8.238-8.345c0-23.443,0.084-46.891-0.038-70.339c-0.051-8.713,6.517-15.682,15.554-15.61
                  c15.75,0.112,31.493,0.022,47.242,0.051c8.107,0.01,16.224-0.039,24.319,0.217c6.353,0.202,12.587,6.59,12.688,12.979
                  c0.18,10.424,0.098,20.844,0.098,31.275c0.025,13.775,0.059,27.535-0.008,41.311c-0.033,5.19,3.935,8.58,8.484,8.541
                  c23.369-0.174,46.751-0.018,70.132-0.112c7.863-0.033,14.631,4.628,15.934,12.454c0.013,0.101,0.189,0.168,0.291,0.268
                  c0,24.81,0,49.618,0,74.426c-0.135,0.313-0.323,0.604-0.389,0.926c-0.715,3.759-2.667,6.757-5.697,9.044
                  c-3.002,2.275-6.471,2.897-10.159,2.897c-27.107-0.033-54.222-0.021-81.34,0c-1.695,0-3.391,0.145-5.083,0.224
                  C134.964,198.143,77.57,198.143,20.165,198.143z M150.552,191.599c10.838,0,21.676,0.01,32.502-0.012
                  c0.881,0,1.784-0.021,2.63-0.233c5.221-1.34,8.207-5.266,8.186-10.963c-0.045-11.429-0.211-22.857-0.266-34.297
                  c-0.045-10.213-0.135-20.438,0.042-30.649c0.122-6.557-4.694-11.189-11.271-11.167c-8.574,0.022-17.16-0.012-25.747,0.022
                  c-13.279,0.05-26.552,0.122-39.82,0.183c-0.535,0.011-1.087,0.073-1.605,0.213c-5.437,1.472-8.212,5.319-8.218,11.449
                  c-0.033,21.109-0.084,42.237-0.069,63.367c0,7.381,4.221,12.242,12.162,12.119C129.567,191.484,140.06,191.599,150.552,191.599z
                  M193.604,53.991c0-11.313,0.01-22.618-0.024-33.924c-0.012-4.974-4.615-9.64-9.578-9.645c-22.278-0.028-44.56-0.028-66.843,0
                  c-4.777,0-9.258,3.897-9.839,8.546c-0.185,1.495,0,3.033-0.03,4.549c-0.111,6.596-0.299,13.186-0.335,19.774
                  c-0.065,14.439-0.182,28.885-0.003,43.313c0.072,6.495,4.326,10.843,10.568,10.877c21.943,0.123,43.893,0.027,65.836,0.066
                  c5.408,0,10.371-4.849,10.29-10.437C193.478,76.073,193.604,65.034,193.604,53.991z M200.527,148.08c0.009,0,0.009,0,0.032,0
                  c0,11.026,0.136,22.075-0.068,33.103c-0.099,5.407,4.66,10.448,10.493,10.436c21.474-0.043,42.928-0.134,64.401,0.046
                  c7.204,0.065,11.911-4.759,11.832-11.964c-0.179-20.415-0.244-40.831-0.379-61.257c0-1.352-0.09-2.731,0.033-4.081
                  c0.456-4.905-5.04-9.969-9.936-9.935c-22.155,0.122-44.312,0.049-66.455,0.055c-5.284,0-9.867,4.193-9.909,9.457
                  C200.458,125.311,200.527,136.695,200.527,148.08z M13.625,147.789c0,11.095,0.145,22.211-0.056,33.316
                  c-0.112,5.854,5.14,10.47,10.547,10.481c21.525,0.031,43.063-0.021,64.581,0.031c6.813,0.025,11.474-4.749,11.411-11.473
                  c-0.182-20.895-0.138-41.813-0.182-62.719c0-1.092-0.072-2.185,0.021-3.266c0.374-4.27-4.219-9.662-9.743-9.668
                  c-22.278-0.025-44.556-0.025-66.834,0c-5.201,0.006-9.722,4.551-9.739,9.779C13.596,125.444,13.619,136.606,13.625,147.789z"/>
            <path fill-rule="evenodd" clip-rule="evenodd" id="Down" fill="#ffffFF" d="M150.552,191.599c-10.492,0-20.984-0.114-31.475,0.032
                  c-7.94,0.123-12.162-4.738-12.162-12.119c-0.015-21.13,0.036-42.258,0.069-63.367c0.006-6.13,2.781-9.978,8.218-11.449
                  c0.518-0.14,1.07-0.202,1.605-0.213c13.268-0.061,26.541-0.133,39.82-0.183c8.587-0.034,17.173,0,25.747-0.022
                  c6.576-0.022,11.393,4.61,11.271,11.167c-0.177,10.212-0.087,20.437-0.042,30.649c0.055,11.439,0.221,22.868,0.266,34.297
                  c0.021,5.697-2.965,9.623-8.186,10.963c-0.846,0.212-1.749,0.233-2.63,0.233C172.228,191.608,161.39,191.599,150.552,191.599z"/>
            <path fill-rule="evenodd" clip-rule="evenodd" id="Up" fill="#ffffff" d="M193.604,53.991c0,11.043-0.126,22.082,0.042,33.119
                  c0.081,5.588-4.882,10.437-10.29,10.437c-21.943-0.039-43.893,0.057-65.836-0.066c-6.243-0.035-10.496-4.383-10.568-10.877
                  c-0.179-14.428-0.063-28.874,0.003-43.313c0.036-6.589,0.224-13.178,0.335-19.774c0.03-1.516-0.155-3.054,0.03-4.549
                  c0.581-4.649,5.062-8.546,9.839-8.546c22.284-0.028,44.565-0.028,66.843,0c4.963,0.005,9.566,4.671,9.578,9.645
                  C193.613,31.374,193.604,42.678,193.604,53.991z"/>
            <path fill-rule="evenodd" clip-rule="evenodd" id="Right" fill="#ffffff" d="M200.527,148.08c0-11.385-0.069-22.77,0.045-34.141
                  c0.042-5.264,4.625-9.457,9.909-9.457c22.144-0.006,44.3,0.067,66.455-0.055c4.896-0.034,10.392,5.029,9.936,9.935
                  c-0.123,1.35-0.033,2.729-0.033,4.081c0.135,20.426,0.2,40.842,0.379,61.257c0.079,7.205-4.628,12.029-11.832,11.964
                  c-21.474-0.18-42.928-0.089-64.401-0.046c-5.833,0.013-10.592-5.028-10.493-10.436c0.204-11.027,0.068-22.076,0.068-33.103
                  C200.536,148.08,200.536,148.08,200.527,148.08z"/>
            <path fill-rule="evenodd" clip-rule="evenodd" id="Left" fill="#FFFFFF" d="M13.619,147.789c0-11.183-0.023-22.345,0.011-33.517
                  c0.017-5.229,4.539-9.773,9.739-9.779c22.278-0.025,44.556-0.025,66.834,0c5.524,0.006,10.117,5.398,9.743,9.668
                  c-0.093,1.081-0.021,2.174-0.021,3.266c0.044,20.906,0,41.824,0.182,62.719c0.063,6.724-4.598,11.498-11.411,11.473
                  c-21.519-0.053-43.056,0-64.581-0.031c-5.407-0.012-10.659-4.628-10.547-10.481C13.77,170,13.625,158.884,13.619,147.789z"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M277.782,150.855c-4.583,2.175-9.153,4.338-14.079,6.689c0-1.994,0-3.713,0-5.663
                  c-6.77,0-13.247,0-19.86,0c0-0.805,0-1.372,0-2.187c6.548,0,13.034,0,19.647,0c0-1.918,0-3.612,0-5.642
                  c0.87,0.346,1.516,0.581,2.152,0.871c3.6,1.706,7.202,3.444,10.813,5.15c0.412,0.189,0.881,0.244,1.326,0.367
                  C277.782,150.577,277.782,150.71,277.782,150.855z"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M136.07,33.875c2.077,4.55,4.164,9.093,6.376,13.933c-1.901,0-3.624,0-5.563,0
                  c0,6.756,0,13.257,0,19.885c-0.786,0-1.348,0-2.024,0c-0.045-0.351-0.116-0.674-0.122-0.997c-0.013-5.582-0.069-11.179,0.011-16.766
                  c0.027-1.571-0.359-2.279-2.036-2.08c-1.127,0.128-2.282,0.023-3.842,0.023c2.359-4.872,4.572-9.445,6.787-13.999
                  C135.788,33.875,135.935,33.875,136.07,33.875z"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M136.753,153.576c2.001,0,3.688,0,5.803,0c-2.302,4.862-4.454,9.398-6.801,14.339
                  c-2.32-4.828-4.488-9.366-6.861-14.306c1.605,0,2.858-0.088,4.102,0.011c1.361,0.112,1.752-0.412,1.741-1.75
                  c-0.072-5.496-0.04-11.004-0.04-16.525c0-0.467,0.06-0.925,0.111-1.561c0.604-0.055,1.172-0.1,1.945-0.177
                  C136.753,140.252,136.753,146.764,136.753,153.576z"/>
            <g>
            <path d="M153.765,47.829l1.994,7.671c0.436,1.685,0.843,3.245,1.127,4.806h0.09c0.344-1.527,0.84-3.155,1.343-4.771l2.469-7.706
                  h2.296l2.345,7.549c0.555,1.808,1,3.401,1.336,4.928h0.102c0.244-1.527,0.646-3.121,1.148-4.894l2.151-7.583h2.721l-4.872,15.098
                  h-2.498l-2.298-7.202c-0.535-1.684-0.969-3.189-1.349-4.962h-0.056c-0.378,1.807-0.847,3.367-1.37,4.984l-2.437,7.18h-2.5
                  l-4.546-15.098H153.765z"/>
            </g>
            <g>
            <path d="M37.096,149.64c0-1.927,0-3.668,0-5.654c-0.986,0.427-1.717,0.749-2.443,1.083c-3.941,1.797-7.877,3.579-11.813,5.374
                  c0,0.135,0,0.268,0,0.413c4.673,2.23,9.354,4.46,14.349,6.847c0-2.208,0-3.937,0-5.923c6.679,0,13.185,0,19.869,0
                  c-0.048-0.824-0.083-1.382-0.131-2.14C50.269,149.64,43.774,149.64,37.096,149.64z"/>
            <path d="M77.396,155.171v-5.642c0-3.033-1.124-6.178-5.737-6.178c-1.901,0-3.712,0.524-4.956,1.337l0.618,1.808
                  c1.068-0.681,2.532-1.115,3.932-1.115c3.088,0,3.435,2.241,3.435,3.489v0.313c-5.833-0.032-9.077,1.963-9.077,5.608
                  c0,2.186,1.555,4.337,4.617,4.337c2.152,0,3.77-1.059,4.616-2.239h0.09l0.218,1.896h2.49
                  C77.465,157.757,77.396,156.476,77.396,155.171z M74.748,153.699c0,0.289-0.06,0.591-0.155,0.881
                  c-0.439,1.271-1.684,2.519-3.65,2.519c-1.399,0-2.589-0.836-2.589-2.619c0-2.932,3.402-3.457,6.395-3.402V153.699z"/>
            </g>
            <g>
            <path d="M157.603,155.983c0.816,0.526,2.249,1.083,3.621,1.083c1.994,0,2.933-0.992,2.933-2.241c0-1.304-0.783-2.029-2.81-2.777
                  c-2.712-0.971-3.994-2.463-3.994-4.271c0-2.431,1.97-4.426,5.219-4.426c1.528,0,2.865,0.436,3.701,0.938l-0.678,1.995
                  c-0.593-0.38-1.687-0.881-3.089-0.881c-1.617,0-2.531,0.937-2.531,2.063c0,1.25,0.914,1.807,2.875,2.554
                  c2.623,1.003,3.959,2.309,3.959,4.561c0,2.642-2.063,4.516-5.643,4.516c-1.661,0-3.189-0.402-4.248-1.026L157.603,155.983z"/>
            </g>
            <g>
            <path d="M233.25,136.639v18.252c0,1.34,0.034,2.866,0.123,3.894h-2.463l-0.122-2.621h-0.058c-0.846,1.683-2.688,2.965-5.15,2.965
                  c-3.645,0-6.447-3.087-6.447-7.671c-0.043-5.017,3.081-8.105,6.762-8.105c2.308,0,3.868,1.092,4.559,2.308h0.057v-9.021H233.25z
                  M230.51,149.841c0-0.347-0.036-0.814-0.123-1.159c-0.403-1.75-1.906-3.178-3.958-3.178c-2.844,0-4.529,2.498-4.529,5.83
                  c0,3.057,1.495,5.577,4.46,5.577c1.843,0,3.525-1.217,4.027-3.268c0.087-0.38,0.123-0.747,0.123-1.193V149.841z"/>
            </g>
            </svg><br><br>
            <?php T("enjoy_playing_snake_in_meantime"); ?>
            

            


            <script type="text/javascript">


                var left = $("#Left", svg);
                var right = $("#Right", svg);
                var up = $("#Up", svg);
                var down = $("#Down", svg);

                var game = init(right, left, up, down);

                var _currentFill = $(".bx-caption").css("background-color"); // red
                var _whiteFill = "#ffffff"; // white
                var svg = $("#SVG_Controls");

                function reactOnKeyPress(keyCode, fill) {
                    if (keyCode === 68 || keyCode === 39) // right
                    {
                        right.attr('style', "fill:" + fill);
                    }
                    if (keyCode === 65 || keyCode === 37) // left
                    {
                        left.attr('style', "fill:" + fill);
                    }
                    if (keyCode === 87 || keyCode === 38) // top
                    {
                        up.attr('style', "fill:" + fill);
                    }
                    if (keyCode === 83 || keyCode === 40) // bottom
                    {
                        down.attr('style', "fill:" + fill);
                    }
                }
   

            </script>
        </div>
    </body>
</html>