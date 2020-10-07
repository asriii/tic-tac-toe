<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tic Tac Toe</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

</head>

<body>
    <div class="container p-5">
        <h4>TIC-TAC-TOE GAME</h4>
        <input type="number" id="inputNumber" value="">
        <button id="create" class="btn btn-primary btn-sm ml-2">สร้าง</button>
        <div class="col pt-3 px-0">
            <form id="form" smethod="POST" onsubmit="return false">
                <table id="table" class="table-bordered">
                    <tbody></tbody>
                </table>
            </form>
            <label id="endgame"></label>
        </div>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="/libs/jQuery-Mask/jquery.mask.min.js"></script>
    <script src="/libs/serializeToJSON/jquery.serializeToJSON.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#create').on("click", function (e) {
                // console.log("e ", e.target.value);
                $('#endgame').text('');
                $('#table tbody').empty();
                var num = $('#inputNumber').val()
                var tr = [];
                var td = [];
                var tb = [];
                for (var i = 0; i < num; i++) {
                    tr.push(i);
                    td.push(i);
                }
                // console.log("tr ", tr);
                // console.log("td ", td);
                tr.forEach(function (vtr, itr) {
                    var temTr = '<tr>';
                    td.forEach(function (vtd, itd) {
                        temTr += '<td>\
                          <div class="d-flex justify-content-center">\
                             <input type="text" name="tictactoe[' + itr + '].[' + itd + ']" onkeyup="checkScore(this)" maxlength="1" class="text-center w-100 xo">\
                           </div>\
                         </td>';
                    });
                    temTr += "</tr>"
                    tb.push(temTr);
                });
                // console.log("tb ", tb);
                $('#table tbody').append(tb);
                inputMask()
            });
        });

        function inputMask() {
            $('.xo').mask('A', {
                translation: {
                    'A': {
                        pattern: /[OXox]/,
                        optional: true
                    }
                }
            });
        }

        function checkScore(e) {
            $('#endgame').text('');
            console.log("e : ", e.value)
            var form = $("#form").serializeToJSON();
            console.log("form : ", form)
            var size = $('#inputNumber').val();
            var objS2 = {
                /* สำหรับเช็คเงื่อนไขคอลลัม */
                col: 0,
                val: "",
                count: 0,
            }
            var objS3 = {
                /* สำหรับเช็คเงื่อนไขเฉียงซ้าย */
                col: 0,
                val: "",
                count: 0,
            }
            var objS4 = {
                /* สำหรับเช็คเงื่อนไขเฉียงขวา */
                col: 0,
                val: "",
                count: 0,
            }

            if (e.value) {
                var count = 0;
                var back = size - 1;
                $.each(form.tictactoe, function (i, v) {
                    var cs1 = conditionRow(v, e.value);
                    /* check condition row */
                    if (cs1 == size) {
                        $('#endgame').text("Player : " + e.value + " = Win");
                        $("#form input").attr("disabled", "disabled")
                        return false;
                    } else {
                        /* check condition col */
                        var cs2 = conditionObj(v, e.value);
                        // console.log("cs2 : ", cs2);
                        if (!objS2.val && cs2.val) {
                            objS2.val = cs2.val;
                            objS2.col = cs2.col;
                            objS2.count = objS2.count + 1;
                        } else if (objS2.val && cs2.val) {
                            if (objS2.val == e.value && cs2.col == objS2.col) {
                                objS2.count = objS2.count + 1;
                            }
                        }
                        // console.log("count : ", objS2.count);
                        if (objS2.count == size) {
                            $('#endgame').text("Player : " + e.value + " = Win");
                            $("#form input").attr("disabled", "disabled");
                            return false;
                        } else {
                            /* check condition เฉียงซ้าย */
                            var cs3 = conditionObj(v, e.value);
                            if (!objS3.val && cs3.val) {
                                if (cs3.col == count) {
                                    objS3.val = cs3.val;
                                    objS3.col = cs3.col;
                                    objS3.count = objS3.count + 1;
                                }
                            } else if (objS3.val && cs3.val) {
                                if (cs3.col == count) {
                                    objS3.count = objS3.count + 1;
                                }
                            }
                            if (objS3.count == size) {
                                $('#endgame').text("Player : " + e.value + " = Win");
                                $("#form input").attr("disabled", "disabled");
                                return false;
                            } else {
                                var cs4 = conditionObj(v, e.value);
                                if (!objS4.val && cs4.val) {
                                    if (cs4.col == back) {
                                        objS4.val = cs4.val;
                                        objS4.col = cs4.col;
                                        objS4.count = objS4.count + 1;
                                    }
                                } else if (objS4.val && cs4.val) {
                                    if (cs4.col == back) {
                                        objS4.count = objS4.count + 1;
                                    }
                                }
                                if (objS4.count == size) {
                                    $('#endgame').text("Player : " + e.value + " = Win");
                                    $("#form input").attr("disabled", "disabled");
                                    return false;
                                }
                            }
                        }
                    }
                    count++;
                    back--;
                });
            }
            // console.log("score row : ", score2);
        }

        /* เงื่อนไข แถว */
        function conditionRow(form, key) {
            var count = 0;
            // console.log("condition1 ", form)
            $.each(form, function (i, v) {
                if (v == key) {
                    count++;
                }
            })
            // console.log("count : ", count)
            return count;
        }

        /* เงื่อนไข คอลลัม */
        function conditionObj(form, key) {
            var obj = {
                col: 0,
                val: ""
            }
            var count = 0;
            $.each(form, function (i, v) {
                if (v == key) {
                    obj.col = count;
                    obj.val = key;
                }
                count++;
            })

            return obj;
        }

    </script>

</body>

</html>
