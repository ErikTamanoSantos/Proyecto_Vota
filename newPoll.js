$(function() {
    showStep();
})

function addAnswer() {
    let answersAmount = $("#answerContainer > div").length
    $("#answerContainer").append(`
        <div>
            <input type="text" placeholder="Respuesta ${answersAmount+1}" name="answers[]">
            <div id="answer${answersAmount+1}ImageButton"><i class="fa-regular fa-image" style="color: #ffffff;"></i></div>
            <input type="file" id="answer${answersAmount+1}Image" name="answerImage${answersAmount+1}" accept="image/png, image/gif, image/jpeg" >
        </div>
    `)
    $(`#answer${answersAmount+1}ImageButton`).on("click", function() {
        $(`#answer${answersAmount+1}Image`).trigger("click")
    })
    $("#removeAnswer").prop("disabled", false)
    if ($("#answerContainer > div").length >= 100) {
        $("#addAnswer").prop("disabled", true)
    }
}

function removeAnswer() {
    $("#answerContainer > div:last-child").remove()
    $("#addAnswer").prop("disabled", false)
    if ($("#answerContainer > div").length <= 2) {
        $("#removeAnswer").prop("disabled", true)
    }
}

function showStep(step = 0) {
    switch (step) {
        case 0:
            $("form").append(`
            <div class="nameQuestionh1">
                <input type="text" id="question" name="question" placeholder="Pregunta">
                <div id="questionImageButton"><i class="fa-regular fa-image" style="color: #ffffff;"></i></div>
                <input type="file" id="questionImage" name="questionImage" accept="image/png, image/gif, image/jpeg, image/webp" >
            </div>
            `)
            $("#question").on('input', function() {
                $(`form > *:gt(${step})`).remove();
                $(window).off("keydown.custom").on("keydown.custom", function(event){
                    if(event.keyCode == 13 || event.keyCode == 9) {
                        event.preventDefault();
                        let question = $("#question").val()
                        if (question.length == "") {
                            showNotification("error", "Inserte una pregunta")
                        } else if (question.includes(";") || question.includes("--") || question.includes("/*") || question.includes("*/")) {
                            showNotification("error", "La pregunta contiene carácteres no permitidos")
                        } else {
                            showStep(step+1)
                        }
                        return false;
                    }
                });
            }).focus()
            $("#questionImageButton").on("click", function() {
                $("#questionImage").trigger("click")
            })
            break;
        case 1:
            $("form").append(`
            <div id="answerContainer">
                <div>
                    <input type="text" class='answerInput' placeholder="Respuesta 1" name="answers[]">
                    <div id="answer1ImageButton"><i class="fa-regular fa-image" style="color: #ffffff;"></i></div>
                    <input type="file" id="answer1Image" name="answerImage1" accept="image/png, image/gif, image/jpeg, image/webp" >
                </div>
                <div>
                    <input type="text" class='answerInput' placeholder="Respuesta 2" name="answers[]">
                    <div id="answer2ImageButton"><i class="fa-regular fa-image" style="color: #ffffff;"></i></div>
                    <input type="file" id="answer2Image" name="answerImage2" accept="image/png, image/gif, image/jpeg, image/webp" >
                </div>
            </div>
            `)
            $("#question").on('input', function() {
                $(`form > *:gt(${step})`).remove();
                $(window).off("keydown.custom").on("keydown.custom", function(event){
                    if(event.keyCode == 13 || event.keyCode == 9) {
                        event.preventDefault();
                        let question = $("#question").val()
                        if (question.length == "") {
                            showNotification("error", "Inserte una pregunta")
                        } else if (question.includes(";") || question.includes("--") || question.includes("/*") || question.includes("*/")) {
                            showNotification("error", "La pregunta contiene carácteres no permitidos")
                        } else {
                            showStep(step+1)
                        }
                        return false;
                    }
                });
            
            $("#answer1ImageButton").on("click", function() {
                $("#answer1Image").trigger("click")
            })
            $("#answer2ImageButton").on("click", function() {
                $("#answer2Image").trigger("click")
            })
            $("#addAnswer").click(addAnswer)
            $("#removeAnswer").click(removeAnswer)
    }
}